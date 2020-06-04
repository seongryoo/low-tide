<?php
/**
 * Authorizer
 *
 * @license  GPL-2.0+
 * @link     https://github.com/uhm-coe/authorizer
 * @package  authorizer
 */

namespace Authorizer;

use Authorizer\Helper;
use Authorizer\Options;

/**
 * Implements the authorization (roles and permissions) features of the plugin.
 */
class Authorization extends Static_Instance {

	/**
	 * This function will fail with a wp_die() message to the user if they
	 * don't have access.
	 *
	 * @param WP_User $user        User to check.
	 * @param array   $user_emails Array of user's plaintext emails (in case current user doesn't have a WP account).
	 * @param array   $user_data   Array of keys for email, username, first_name, last_name,
	 *                             authenticated_by, google_attributes, cas_attributes, ldap_attributes.
	 * @return WP_Error|WP_User
	 *                             WP_Error if there was an error on user creation / adding user to blog.
	 *                             WP_Error / wp_die() if user does not have access.
	 *                             WP_User if user has access.
	 */
	public function check_user_access( $user, $user_emails, $user_data = array() ) {
		// Grab plugin settings.
		$options                                    = Options::get_instance();
		$auth_settings                              = $options->get_all( Helper::SINGLE_CONTEXT, 'allow override' );
		$auth_settings_access_users_pending         = $options->sanitize_user_list(
			$options->get( 'access_users_pending', Helper::SINGLE_CONTEXT )
		);
		$auth_settings_access_users_approved_single = $options->get( 'access_users_approved', Helper::SINGLE_CONTEXT );
		$auth_settings_access_users_approved_multi  = $options->get( 'access_users_approved', Helper::NETWORK_CONTEXT );
		$auth_settings_access_users_approved        = $options->sanitize_user_list(
			array_merge(
				$auth_settings_access_users_approved_single,
				$auth_settings_access_users_approved_multi
			)
		);

		/**
		 * Filter whether to block the currently logging in user based on any of
		 * their user attributes.
		 *
		 * @param bool $allow_login Whether to block the currently logging in user.
		 * @param array $user_data User data returned from external service.
		 */
		$allow_login       = apply_filters( 'authorizer_allow_login', true, $user_data );
		$blocked_by_filter = ! $allow_login; // Use this for better readability.

		// Check our externally authenticated user against the block list.
		// If any of their email addresses are blocked, set the relevant user
		// meta field, and show them an error screen.
		foreach ( $user_emails as $user_email ) {
			if ( $blocked_by_filter || $this->is_email_in_list( $user_email, 'blocked' ) ) {

				// Add user to blocked list if it was blocked via the filter.
				if ( $blocked_by_filter && ! $this->is_email_in_list( $user_email, 'blocked' ) ) {
					$auth_settings_access_users_blocked = $options->sanitize_user_list(
						$options->get( 'access_users_blocked', Helper::SINGLE_CONTEXT )
					);
					array_push(
						$auth_settings_access_users_blocked,
						array(
							'email'      => Helper::lowercase( $user_email ),
							'date_added' => date( 'M Y' ),
						)
					);
					update_option( 'auth_settings_access_users_blocked', $auth_settings_access_users_blocked );
				}

				// If the blocked external user has a WordPress account, mark it as
				// blocked (enforce block in this->authenticate()).
				if ( $user ) {
					update_user_meta( $user->ID, 'auth_blocked', 'yes' );
				}

				// Notify user about blocked status and return without authenticating them.
				// phpcs:ignore WordPress.Security.NonceVerification
				$redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : home_url();
				$page_title  = sprintf(
					/* TRANSLATORS: %s: Name of blog */
					__( '%s - Access Restricted', 'authorizer' ),
					get_bloginfo( 'name' )
				);
				$error_message =
					apply_filters( 'the_content', $auth_settings['access_blocked_redirect_to_message'] ) .
					'<hr />' .
					'<p style="text-align: center;">' .
					'<a class="button" href="' . wp_logout_url( $redirect_to ) . '">' .
					__( 'Back', 'authorizer' ) .
					'</a></p>';
				update_option( 'auth_settings_advanced_login_error', $error_message );
				wp_die( wp_kses( $error_message, Helper::$allowed_html ), esc_html( $page_title ) );
				return new \WP_Error( 'invalid_login', __( 'Invalid login attempted.', 'authorizer' ) );
			}
		}

		// Get the default role for this user (or their current role, if they
		// already have an account).
		$default_role = $user && is_array( $user->roles ) && count( $user->roles ) > 0 ? $user->roles[0] : $auth_settings['access_default_role'];
		/**
		 * Filter the role of the user currently logging in. The role will be
		 * set to the default (specified in Authorizer options) for new users,
		 * or the user's current role for existing users. This filter allows
		 * changing user roles based on custom CAS/LDAP attributes.
		 *
		 * @param bool $role Role of the user currently logging in.
		 * @param array $user_data User data returned from external service.
		 */
		$approved_role = apply_filters( 'authorizer_custom_role', $default_role, $user_data );

		/**
		 * Filter whether to automatically approve the currently logging in user
		 * based on any of their user attributes.
		 *
		 * @param bool  $automatically_approve_login
		 *   Whether to automatically approve the currently logging in user.
		 * @param array $user_data User data returned from external service.
		 */
		$automatically_approve_login = apply_filters( 'authorizer_automatically_approve_login', false, $user_data );

		// Iterate through each of the email addresses provided by the external
		// service and determine if any of them have access.
		$last_email = end( $user_emails );
		reset( $user_emails );
		foreach ( $user_emails as $user_email ) {
			$is_newly_approved_user = false;

			// If this externally authenticated user is an existing administrator
			// (administrator in single site mode, or super admin in network mode),
			// and is not in the blocked list, let them in.
			if ( $user && is_super_admin( $user->ID ) ) {
				return $user;
			}

			// If this externally authenticated user isn't in the approved list
			// and login access is set to "All authenticated users," or if they were
			// automatically approved in the "authorizer_approve_login" filter
			// above, then add them to the approved list (they'll get an account
			// created below if they don't have one yet).
			if (
				! $this->is_email_in_list( $user_email, 'approved' ) &&
				( 'external_users' === $auth_settings['access_who_can_login'] || $automatically_approve_login )
			) {
				$is_newly_approved_user = true;

				// If this user happens to be in the pending list (rare),
				// remove them from pending before adding them to approved.
				if ( $this->is_email_in_list( $user_email, 'pending' ) ) {
					foreach ( $auth_settings_access_users_pending as $key => $pending_user ) {
						if ( 0 === strcasecmp( $pending_user['email'], $user_email ) ) {
							unset( $auth_settings_access_users_pending[ $key ] );
							update_option( 'auth_settings_access_users_pending', $auth_settings_access_users_pending );
							break;
						}
					}
				}

				// Add this user to the approved list.
				$approved_user = array(
					'email'      => Helper::lowercase( $user_email ),
					'role'       => $approved_role,
					'date_added' => date( 'Y-m-d H:i:s' ),
				);
				array_push( $auth_settings_access_users_approved, $approved_user );
				array_push( $auth_settings_access_users_approved_single, $approved_user );
				update_option( 'auth_settings_access_users_approved', $auth_settings_access_users_approved_single );
			}

			// Check our externally authenticated user against the approved
			// list. If they are approved, log them in (and create their account
			// if necessary).
			if ( $is_newly_approved_user || $this->is_email_in_list( $user_email, 'approved' ) ) {
				$user_info = $is_newly_approved_user ? $approved_user : Helper::get_user_info_from_list( $user_email, $auth_settings_access_users_approved );

				// If this user's role was modified above (in the
				// authorizer_custom_role filter), use that value instead of
				// whatever is specified in the approved list.
				if ( $default_role !== $approved_role ) {
					$user_info['role'] = $approved_role;
				}

				// If the approved external user does not have a WordPress account, create it.
				if ( ! $user ) {
					if ( array_key_exists( 'username', $user_data ) ) {
						$username = $user_data['username'];
					} else {
						$username = explode( '@', $user_info['email'] );
						$username = $username[0];
					}
					// If there's already a user with this username (e.g.,
					// johndoe/johndoe@gmail.com exists, and we're trying to add
					// johndoe/johndoe@example.com), use the full email address
					// as the username.
					if ( get_user_by( 'login', $username ) !== false ) {
						$username = $user_info['email'];
					}
					$result = wp_insert_user(
						array(
							'user_login'      => strtolower( $username ),
							'user_pass'       => wp_generate_password(), // random password.
							'first_name'      => array_key_exists( 'first_name', $user_data ) ? $user_data['first_name'] : '',
							'last_name'       => array_key_exists( 'last_name', $user_data ) ? $user_data['last_name'] : '',
							'user_email'      => Helper::lowercase( $user_info['email'] ),
							'user_registered' => date( 'Y-m-d H:i:s' ),
							'role'            => $user_info['role'],
						)
					);

					// Fail with message if error.
					if ( is_wp_error( $result ) || 0 === $result ) {
						return $result;
					}

					// Authenticate as new user.
					$user = new \WP_User( $result );

					/**
					 * Fires after an external user is authenticated for the first time
					 * and a new WordPress account is created for them.
					 *
					 * @since 2.8.0
					 *
					 * @param WP_User $user      User object.
					 * @param array   $user_data User data from external service.
					 *
					 * Example $user_data:
					 * array(
					 *   'email'            => 'user@example.edu',
					 *   'username'         => 'user',
					 *   'first_name'       => 'First',
					 *   'last_name'        => 'Last',
					 *   'authenticated_by' => 'cas',
					 *   'cas_attributes'   => array( ... ),
					 * );
					 */
					do_action( 'authorizer_user_register', $user, $user_data );

					// If multisite, iterate through all sites in the network and add the user
					// currently logging in to any of them that have the user on the approved list.
					// Note: this is useful for first-time logins--some users will have access
					// to multiple sites, and this prevents them from having to log into each
					// site individually to get access.
					if ( is_multisite() ) {
						$site_ids_of_user = array_map(
							function ( $site_of_user ) {
								return intval( $site_of_user->userblog_id );
							},
							get_blogs_of_user( $user->ID )
						);

						// phpcs:ignore WordPress.WP.DeprecatedFunctions.wp_get_sitesFound
						$sites = function_exists( 'get_sites' ) ? get_sites() : wp_get_sites( array( 'limit' => PHP_INT_MAX ) );
						foreach ( $sites as $site ) {
							$blog_id = function_exists( 'get_sites' ) ? $site->blog_id : $site['blog_id'];

							// Skip if user is already added to this site.
							if ( in_array( intval( $blog_id ), $site_ids_of_user, true ) ) {
								continue;
							}

							// Check if user is on the approved list of this site they are not added to.
							$other_auth_settings_access_users_approved = get_blog_option( $blog_id, 'auth_settings_access_users_approved', array() );
							if ( Helper::in_multi_array( $user->user_email, $other_auth_settings_access_users_approved ) ) {
								$other_user_info = Helper::get_user_info_from_list( $user->user_email, $other_auth_settings_access_users_approved );
								// Add user to other site.
								add_user_to_blog( $blog_id, $user->ID, $other_user_info['role'] );
							}
						}
					}

					// Check if this new user has any preassigned usermeta
					// values in their approved list entry, and apply them to
					// their new WordPress account.
					if ( array_key_exists( 'usermeta', $user_info ) && is_array( $user_info['usermeta'] ) ) {
						$meta_key = $options->get( 'advanced_usermeta' );

						if ( array_key_exists( 'meta_key', $user_info['usermeta'] ) && array_key_exists( 'meta_value', $user_info['usermeta'] ) ) {
							// Only update the usermeta if the stored value matches
							// the option set in authorizer settings (if they don't
							// match it's probably old data).
							if ( $meta_key === $user_info['usermeta']['meta_key'] ) {
								// Update user's usermeta value for usermeta key stored in authorizer options.
								if ( strpos( $meta_key, 'acf___' ) === 0 && class_exists( 'acf' ) ) {
									// We have an ACF field value, so use the ACF function to update it.
									update_field( str_replace( 'acf___', '', $meta_key ), $user_info['usermeta']['meta_value'], 'user_' . $user->ID );
								} else {
									// We have a normal usermeta value, so just update it via the WordPress function.
									update_user_meta( $user->ID, $meta_key, $user_info['usermeta']['meta_value'] );
								}
							}
						} elseif ( is_multisite() && count( $user_info['usermeta'] ) > 0 ) {
							// Update usermeta for each multisite blog defined for this user.
							foreach ( $user_info['usermeta'] as $blog_id => $usermeta ) {
								if ( array_key_exists( 'meta_key', $usermeta ) && array_key_exists( 'meta_value', $usermeta ) ) {
									// Add this new user to the blog before we create their user meta (this step typically happens below, but we need it to happen early so we can create user meta here).
									if ( ! is_user_member_of_blog( $user->ID, $blog_id ) ) {
										add_user_to_blog( $blog_id, $user->ID, $user_info['role'] );
									}
									switch_to_blog( $blog_id );
									// Update user's usermeta value for usermeta key stored in authorizer options.
									if ( strpos( $meta_key, 'acf___' ) === 0 && class_exists( 'acf' ) ) {
										// We have an ACF field value, so use the ACF function to update it.
										update_field( str_replace( 'acf___', '', $meta_key ), $usermeta['meta_value'], 'user_' . $user->ID );
									} else {
										// We have a normal usermeta value, so just update it via the WordPress function.
										update_user_meta( $user->ID, $meta_key, $usermeta['meta_value'] );
									}
									restore_current_blog();
								}
							}
						}
					}
				} else {
					// Update first/last names of WordPress user from external
					// service if that option is set.
					if ( ( array_key_exists( 'authenticated_by', $user_data ) && 'cas' === $user_data['authenticated_by'] && array_key_exists( 'cas_attr_update_on_login', $auth_settings ) && 1 === intval( $auth_settings['cas_attr_update_on_login'] ) ) || ( array_key_exists( 'authenticated_by', $user_data ) && 'ldap' === $user_data['authenticated_by'] && array_key_exists( 'ldap_attr_update_on_login', $auth_settings ) && 1 === intval( $auth_settings['ldap_attr_update_on_login'] ) ) ) {
						if ( array_key_exists( 'first_name', $user_data ) && 0 < strlen( $user_data['first_name'] ) ) {
							wp_update_user(
								array(
									'ID'         => $user->ID,
									'first_name' => $user_data['first_name'],
								)
							);
						}
						if ( array_key_exists( 'last_name', $user_data ) && strlen( $user_data['last_name'] ) > 0 ) {
							wp_update_user(
								array(
									'ID'        => $user->ID,
									'last_name' => $user_data['last_name'],
								)
							);
						}
					}

					// Update this user's role if it was modified in the
					// authorizer_custom_role filter.
					if ( $default_role !== $approved_role ) {
						// Update user's role in WordPress.
						$user->set_role( $approved_role );

						// Update user's role in this site's approved list and save.
						foreach ( $auth_settings_access_users_approved_single as $key => $existing_user ) {
							if ( 0 === strcasecmp( $user->user_email, $existing_user['email'] ) ) {
								$auth_settings_access_users_approved_single[ $key ]['role'] = $approved_role;
								break;
							}
						}
						update_option( 'auth_settings_access_users_approved', $auth_settings_access_users_approved_single );
					}
				}

				// If this is multisite, add new user to current blog.
				if ( is_multisite() && ! is_user_member_of_blog( $user->ID ) ) {
					$result = add_user_to_blog( get_current_blog_id(), $user->ID, $user_info['role'] );

					// Fail with message if error.
					if ( is_wp_error( $result ) ) {
						return $result;
					}
				}

				// Ensure user has the same role as their entry in the approved list.
				if ( $user_info && ! in_array( $user_info['role'], $user->roles, true ) ) {
					$user->set_role( $user_info['role'] );
				}

				return $user;

			} elseif ( 0 === strcasecmp( $user_email, $last_email ) ) {
				/**
				 * Note: only do this for the last email address we are checking (we need
				 * to iterate through them all to make sure one of them isn't approved).
				 */

				// User isn't an admin, is not blocked, and is not approved.
				// Add them to the pending list and notify them and their instructor.
				if ( strlen( $user_email ) > 0 && ! $this->is_email_in_list( $user_email, 'pending' ) ) {
					$pending_user               = array();
					$pending_user['email']      = Helper::lowercase( $user_email );
					$pending_user['role']       = $approved_role;
					$pending_user['date_added'] = '';
					array_push( $auth_settings_access_users_pending, $pending_user );
					update_option( 'auth_settings_access_users_pending', $auth_settings_access_users_pending );

					// Create strings used in the email notification.
					$site_name              = get_bloginfo( 'name' );
					$site_url               = get_bloginfo( 'url' );
					$authorizer_options_url = 'settings' === $auth_settings['advanced_admin_menu'] ? admin_url( 'options-general.php?page=authorizer' ) : admin_url( '?page=authorizer' );

					// Notify users with the role specified in "Which role should
					// receive email notifications about pending users?".
					if ( strlen( $auth_settings['access_role_receive_pending_emails'] ) > 0 ) {
						foreach ( get_users( array( 'role' => $auth_settings['access_role_receive_pending_emails'] ) ) as $user_recipient ) {
							wp_mail(
								$user_recipient->user_email,
								sprintf(
									/* TRANSLATORS: 1: User email 2: Name of site */
									__( 'Action required: Pending user %1$s at %2$s', 'authorizer' ),
									$pending_user['email'],
									$site_name
								),
								sprintf(
									/* TRANSLATORS: 1: Name of site 2: URL of site 3: URL of authorizer */
									__( "A new user has tried to access the %1\$s site you manage at:\n%2\$s\n\nPlease log in to approve or deny their request:\n%3\$s\n", 'authorizer' ),
									$site_name,
									$site_url,
									$authorizer_options_url
								)
							);
						}
					}
				}

				// Fetch the external service this user authenticated with, and append
				// it to the logout URL below (so we can fire custom logout routines in
				// custom_logout() based on their external service. This is necessary
				// because a pending user does not have a WP_User, and thus no
				// "authenticated_by" usermeta that is normally used to do this.
				$external_param = isset($user_data['authenticated_by']) ? '&external=' . $user_data['authenticated_by'] : '';

				// Notify user about pending status and return without authenticating them.
				// phpcs:ignore WordPress.Security.NonceVerification
				$redirect_to   = ! empty( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : home_url();
				$page_title    = get_bloginfo( 'name' ) . ' - Access Pending';
				$error_message =
					apply_filters( 'the_content', $auth_settings['access_pending_redirect_to_message'] ) .
					'<hr />' .
					'<p style="text-align: center;">' .
					'<a class="button" href="' . wp_logout_url( $redirect_to ) . $external_param . '">' .
					__( 'Back', 'authorizer' ) .
					'</a></p>';
				update_option( 'auth_settings_advanced_login_error', $error_message );
				wp_die( wp_kses( $error_message, Helper::$allowed_html ), esc_html( $page_title ) );
			}
		}

		// Sanity check: if we made it here without returning, something has gone wrong.
		return new \WP_Error( 'invalid_login', __( 'Invalid login attempted.', 'authorizer' ) );

	}


	/**
	 * Restrict access to WordPress site based on settings (everyone, logged_in_users).
	 *
	 * Action: parse_request
	 *
	 * @param  array $wp WordPress object.
	 * @return WP|void   WP object when passing through to WordPress authentication, or void.
	 */
	public function restrict_access( $wp ) {
		// Grab plugin settings.
		$options       = Options::get_instance();
		$auth_settings = $options->get_all( Helper::SINGLE_CONTEXT, 'allow override' );

		// Grab current user.
		$current_user = wp_get_current_user();

		$has_access = (
			// Always allow access if WordPress is installing.
			// phpcs:ignore WordPress.Security.NonceVerification
			( defined( 'WP_INSTALLING' ) && isset( $_GET['key'] ) ) ||
			// Always allow access to admins.
			( current_user_can( 'create_users' ) ) ||
			// Allow access if option is set to 'everyone'.
			( 'everyone' === $auth_settings['access_who_can_view'] ) ||
			// Allow access to approved external users and logged in users if option is set to 'logged_in_users'.
			( 'logged_in_users' === $auth_settings['access_who_can_view'] && Helper::is_user_logged_in_and_blog_user() && $this->is_email_in_list( $current_user->user_email, 'approved' ) ) ||
			// Allow access for requests to /wp-json/oauth1 so oauth clients can authenticate to use the REST API.
			( property_exists( $wp, 'matched_query' ) && stripos( $wp->matched_query, 'rest_oauth1=' ) === 0 ) ||
			// Allow access for non-GET requests to /wp-json/*, since REST API authentication already covers them.
			( property_exists( $wp, 'matched_query' ) && 0 === stripos( $wp->matched_query, 'rest_route=' ) && isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' !== $_SERVER['REQUEST_METHOD'] ) ||
			// Allow access for GET requests to /wp-json/ (root), since REST API discovery calls rely on this.
			( property_exists( $wp, 'matched_query' ) && 'rest_route=/' === $wp->matched_query )
			// Note that GET requests to a rest endpoint will be restricted by authorizer. In that case, error messages will be returned as JSON.
		);

		/**
		 * Developers can use the `authorizer_has_access` filter to override
		 * restricted access on certain pages. Note that the restriction checks
		 * happens before WordPress executes any queries, so use the $wp variable
		 * to investigate what the visitor is trying to load.
		 *
		 * For example, to unblock an RSS feed, place the following PHP code in
		 * the theme's functions.php file or in a simple plug-in:
		 *
		 *   function my_feed_access_override( $has_access, $wp ) {
		 *     // Check query variables to see if this is the feed.
		 *     if ( ! empty( $wp->query_vars['feed'] ) ) {
		 *       $has_access = true;
		 *     }
		 *
		 *     return $has_access;
		 *   }
		 *   add_filter( 'authorizer_has_access', 'my_feed_access_override', 10, 2 );
		 */
		if ( apply_filters( 'authorizer_has_access', $has_access, $wp ) === true ) {
			// Turn off the public notice about browsing anonymously.
			update_option( 'auth_settings_advanced_public_notice', false );

			// We've determined that the current user has access, so simply return to grant access.
			return $wp;
		}

		// Allow HEAD requests to the root (usually discovery from a REST client).
		if ( 'HEAD' === $_SERVER['REQUEST_METHOD'] && empty( $wp->request ) && empty( $wp->matched_query ) ) {
			return $wp;
		}

		/* We've determined that the current user doesn't have access, so we deal with them now. */

		// Fringe case: In a multisite, a user of a different blog can successfully
		// log in, but they aren't on the 'approved' whitelist for this blog.
		// If that's the case, add them to the pending list for this blog.
		if ( is_multisite() && is_user_logged_in() && ! $has_access ) {
			$current_user = wp_get_current_user();

			// Check user access; block if not, add them to pending list if open, let them through otherwise.
			$result = $this->check_user_access( $current_user, array( $current_user->user_email ) );
		}

		// Check to see if the requested page is public. If so, show it.
		if ( empty( $wp->request ) ) {
			$current_page_id = 'home';
		} else {
			$request_query   = isset( $wp->query_vars ) ? new \WP_Query( $wp->query_vars ) : null;
			$current_page_id = isset( $request_query->post_count ) && $request_query->post_count > 0 ? $request_query->post->ID : '';
		}
		if ( ! array_key_exists( 'access_public_pages', $auth_settings ) || ! is_array( $auth_settings['access_public_pages'] ) ) {
			$auth_settings['access_public_pages'] = array();
		}
		if ( in_array( strval( $current_page_id ), $auth_settings['access_public_pages'], true ) ) {
			if ( 'no_warning' === $auth_settings['access_public_warning'] ) {
				update_option( 'auth_settings_advanced_public_notice', false );
			} else {
				update_option( 'auth_settings_advanced_public_notice', true );
			}
			return $wp;
		}

		// Check to see if any category assigned to the requested page is public. If so, show it.
		$current_page_categories = wp_get_post_categories( $current_page_id, array( 'fields' => 'slugs' ) );
		foreach ( $current_page_categories as $current_page_category ) {
			if ( in_array( 'cat_' . $current_page_category, $auth_settings['access_public_pages'], true ) ) {
				if ( 'no_warning' === $auth_settings['access_public_warning'] ) {
					update_option( 'auth_settings_advanced_public_notice', false );
				} else {
					update_option( 'auth_settings_advanced_public_notice', true );
				}
				return $wp;
			}
		}

		// Check to see if this page can't be found. If so, allow showing the 404 page.
		if ( strlen( $current_page_id ) < 1 ) {
			if ( in_array( 'auth_public_404', $auth_settings['access_public_pages'], true ) ) {
				if ( 'no_warning' === $auth_settings['access_public_warning'] ) {
					update_option( 'auth_settings_advanced_public_notice', false );
				} else {
					update_option( 'auth_settings_advanced_public_notice', true );
				}
				return $wp;
			}
		}

		// Check to see if the requested category is public. If so, show it.
		$current_category_name = property_exists( $wp, 'query_vars' ) && array_key_exists( 'category_name', $wp->query_vars ) && strlen( $wp->query_vars['category_name'] ) > 0 ? $wp->query_vars['category_name'] : '';
		if ( $current_category_name ) {
			$current_category_name = end( explode( '/', $current_category_name ) );
			if ( in_array( 'cat_' . $current_category_name, $auth_settings['access_public_pages'], true ) ) {
				if ( 'no_warning' === $auth_settings['access_public_warning'] ) {
					update_option( 'auth_settings_advanced_public_notice', false );
				} else {
					update_option( 'auth_settings_advanced_public_notice', true );
				}
				return $wp;
			}
		}

		// User is denied access, so show them the error message. Render as JSON
		// if this is a REST API call; otherwise, show the error message via
		// wp_die() (rendered html), or redirect to the login URL.
		$current_path = ! empty( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : home_url();
		if ( property_exists( $wp, 'matched_query' ) && stripos( $wp->matched_query, 'rest_route=' ) === 0 && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			wp_send_json(
				array(
					'code'    => 'rest_cannot_view',
					'message' => wp_strip_all_tags( $auth_settings['access_redirect_to_message'] ),
					'data'    => array(
						'status' => 401,
					),
				)
			);
		} elseif ( 'message' === $auth_settings['access_redirect'] ) {
			$page_title = sprintf(
				/* TRANSLATORS: %s: Name of blog */
				__( '%s - Access Restricted', 'authorizer' ),
				get_bloginfo( 'name' )
			);
			$error_message =
				apply_filters( 'the_content', $auth_settings['access_redirect_to_message'] ) .
				'<hr />' .
				'<p style="text-align: center;margin-bottom: -15px;">' .
				'<a class="button" href="' . wp_login_url( $current_path ) . '">' .
				__( 'Log In', 'authorizer' ) .
				'</a></p>';
			wp_die( wp_kses( $error_message, Helper::$allowed_html ), esc_html( $page_title ) );
		} else {
			wp_safe_redirect( wp_login_url( $current_path ), 302 );
			exit;
		}

		// Sanity check: we should never get here.
		wp_die( '<p>Access denied.</p>', 'Site Access Restricted' );
	}


	/**
	 * Helper function to determine whether a given email is in one of
	 * the lists (pending, approved, blocked). Defaults to the list of
	 * approved users.
	 *
	 * @param  string $email          Email to check existent of.
	 * @param  string $list           List to look for email in.
	 * @param  string $multisite_mode Admin context.
	 * @return boolean                Whether email was found.
	 */
	public function is_email_in_list( $email = '', $list = 'approved', $multisite_mode = 'single' ) {
		if ( empty( $email ) ) {
			return false;
		}

		$options = Options::get_instance();

		switch ( $list ) {
			case 'pending':
				$auth_settings_access_users_pending = $options->get( 'access_users_pending', Helper::SINGLE_CONTEXT );
				return Helper::in_multi_array( $email, $auth_settings_access_users_pending );
			case 'blocked':
				$auth_settings_access_users_blocked = $options->get( 'access_users_blocked', Helper::SINGLE_CONTEXT );
				// Blocked list can have wildcard matches, e.g., @baddomain.com, which
				// should match any email address at that domain. Check if any wildcards
				// exist, and if the email address has that domain.
				$email_in_blocked_domain = false;
				$blocked_domains         = preg_grep(
					'/^@.*/',
					array_map(
						function ( $blocked_item ) {
							return $blocked_item['email']; },
						$auth_settings_access_users_blocked
					)
				);
				foreach ( $blocked_domains as $blocked_domain ) {
					$email_domain = substr( $email, strrpos( $email, '@' ) );
					if ( $email_domain === $blocked_domain ) {
						$email_in_blocked_domain = true;
						break;
					}
				}
				return $email_in_blocked_domain || Helper::in_multi_array( $email, $auth_settings_access_users_blocked );
			case 'approved':
			default:
				if ( 'single' !== $multisite_mode ) {
					// Get multisite users only.
					$auth_settings_access_users_approved = $options->get( 'access_users_approved', Helper::NETWORK_CONTEXT );
				} elseif ( is_multisite() && 1 === intval( $options->get( 'advanced_override_multisite' ) ) ) {
					// This site has overridden any multisite settings, so only get its users.
					$auth_settings_access_users_approved = $options->get( 'access_users_approved', Helper::SINGLE_CONTEXT );
				} else {
					// Get all site users and all multisite users.
					$auth_settings_access_users_approved = array_merge(
						$options->get( 'access_users_approved', Helper::SINGLE_CONTEXT ),
						$options->get( 'access_users_approved', Helper::NETWORK_CONTEXT )
					);
				}
				return Helper::in_multi_array( $email, $auth_settings_access_users_approved );
		}
	}

}
