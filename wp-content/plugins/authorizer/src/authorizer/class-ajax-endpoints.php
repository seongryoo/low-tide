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
use Authorizer\Options\Access_Lists;
use Authorizer\Authorization;
use Authorizer\Sync_Userdata;

/**
 * Contains endpoints for any AJAX methods.
 */
class Ajax_Endpoints extends Static_Instance {

	/**
	 * Verify the Google login and set a session token.
	 *
	 * Flow: "Sign in with Google" button clicked; JS Google library
	 * called; JS function signInCallback() fired with results from Google;
	 * signInCallback() posts code and nonce (via AJAX) to this function;
	 * This function checks the token using the Google PHP library, and
	 * saves it to a session variable if it's authentic; control passes
	 * back to signInCallback(), which will reload the current page
	 * (wp-login.php) on success; wp-login.php reloads; custom_authenticate
	 * hooked into authenticate action fires again, and
	 * custom_authenticate_google() runs to verify the token; once verified
	 * custom_authenticate proceeds as normal with the google email address
	 * as a successfully authenticated external user.
	 *
	 * Action: wp_ajax_process_google_login
	 * Action: wp_ajax_nopriv_process_google_login
	 *
	 * @return void, but die with the value to return to the success() function in AJAX call signInCallback().
	 */
	public function ajax_process_google_login() {
		// Nonce check.
		if (
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'google_csrf_nonce' )
		) {
			die( '' );
		}

		// Google authentication token.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$code = isset( $_POST['code'] ) ? wp_unslash( $_POST['code'] ) : null;

		// Grab plugin settings.
		$options       = Options::get_instance();
		$auth_settings = $options->get_all( Helper::SINGLE_CONTEXT, 'allow override' );

		// Add Google API PHP Client.
		// @see https://github.com/googleapis/google-api-php-client/releases v2.2.4_PHP54
		if ( ! class_exists( 'Google_Client' ) ) {
			require_once dirname( plugin_root() ) . '/vendor/google-api-php-client-v2/vendor/autoload.php';
		}

		// Build the Google Client.
		$client = new \Google_Client();
		$client->setApplicationName( 'WordPress' );
		$client->setClientId( $auth_settings['google_clientid'] );
		$client->setClientSecret( $auth_settings['google_clientsecret'] );
		$client->setRedirectUri( 'postmessage' );

		/**
		 * If the hosted domain parameter is set, restrict logins to that domain
		 * (only available in google-api-php-client v2 or higher).
		 */
		if (
			array_key_exists( 'google_hosteddomain', $auth_settings ) &&
			strlen( $auth_settings['google_hosteddomain'] ) > 0 &&
			$client::LIBVER >= '2.0.0'
		) {
			$google_hosteddomains = explode( "\n", str_replace( "\r", '', $auth_settings['google_hosteddomain'] ) );
			$google_hosteddomain = trim( $google_hosteddomains[0] );
			$client->setHostedDomain( $google_hosteddomain );
		}

		// Get one time use token (if it doesn't exist, we'll create one below).
		session_start();
		if ( empty( $_SESSION['token'] ) ) {
			// Exchange the OAuth 2.0 authorization code for user credentials.
			$client->authenticate( $code );

			// Edge case: if another plugin has already defined the Google_Client class,
			// and it's a version earlier than v2, then we need to handle $token as a
			// json-encoded string instead of an array.
			if ( $client::LIBVER < '2.0.0' ) {
				$token = json_decode( $client->getAccessToken(), true );
			} else {
				$token = $client->getAccessToken();
			}

			// Store the token in the session for later use.
			$_SESSION['token'] = wp_json_encode( $token );

			$response = 'Successfully authenticated.';
		} else {
			// Edge case: if another plugin has already defined the Google_Client class,
			// and it's a version earlier than v2, then we need to send a json-encoded
			// string to setAccessToken() instead of an array.
			if ( $client::LIBVER < '2.0.0' ) {
				$client->setAccessToken( $_SESSION['token'] );
			} else {
				$client->setAccessToken( json_decode( $_SESSION['token'], true ) );
			}

			$response = 'Already authenticated.';
		}

		die( esc_html( $response ) );
	}


	/**
	 * Save multisite settings (ajax call).
	 *
	 * Action: wp_ajax_save_auth_multisite_settings
	 */
	public function ajax_save_auth_multisite_settings() {
		// Fail silently if current user doesn't have permissions.
		if ( ! current_user_can( 'manage_network_options' ) ) {
			die( '' );
		}

		// Make sure nonce exists.
		if ( empty( $_POST['nonce'] ) ) {
			die( '' );
		}

		// Nonce check.
		if ( ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'save_auth_settings' ) ) {
			die( '' );
		}

		// Assert multisite.
		if ( ! is_multisite() ) {
			die( '' );
		}

		// Get options object.
		$options = Options::get_instance();

		// Get multisite settings.
		$auth_multisite_settings = get_blog_option( get_network()->blog_id, 'auth_multisite_settings', array() );

		// Sanitize settings.
		$auth_multisite_settings = $options->sanitize_options( $_POST );

		// Filter options to only the allowed values (multisite options are a subset of all options).
		$allowed                 = array(
			'multisite_override',
			'access_who_can_login',
			'access_who_can_view',
			'access_default_role',
			'google',
			'google_clientid',
			'google_clientsecret',
			'google_hosteddomain',
			'cas',
			'cas_custom_label',
			'cas_host',
			'cas_port',
			'cas_path',
			'cas_version',
			'cas_attr_email',
			'cas_attr_first_name',
			'cas_attr_last_name',
			'cas_attr_update_on_login',
			'cas_auto_login',
			'cas_link_on_username',
			'ldap',
			'ldap_host',
			'ldap_port',
			'ldap_tls',
			'ldap_search_base',
			'ldap_uid',
			'ldap_attr_email',
			'ldap_user',
			'ldap_password',
			'ldap_lostpassword_url',
			'ldap_attr_first_name',
			'ldap_attr_last_name',
			'ldap_attr_update_on_login',
			'advanced_lockouts',
			'advanced_hide_wp_login',
			'advanced_disable_wp_login',
			'advanced_users_per_page',
			'advanced_users_sort_by',
			'advanced_users_sort_order',
			'advanced_widget_enabled',
		);
		$auth_multisite_settings = array_intersect_key( $auth_multisite_settings, array_flip( $allowed ) );

		// Update multisite settings in database.
		update_blog_option( get_network()->blog_id, 'auth_multisite_settings', $auth_multisite_settings );

		// Return 'success' value to AJAX call.
		die( 'success' );
	}



	/**
	 * Re-render the Approved User list (usually triggered if pager params have
	 * changed, e.g., current page, search term, sort order).
	 *
	 * Action: wp_ajax_refresh_approved_user_list
	 *
	 * @return void
	 */
	public function ajax_refresh_approved_user_list() {
		// Fail silently if current user doesn't have permissions.
		if ( ! current_user_can( 'create_users' ) ) {
			die( '' );
		}

		// Nonce check.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'save_auth_settings' ) ) {
			die( '' );
		}

		// Fail if required post data doesn't exist.
		if ( ! array_key_exists( 'paged', $_REQUEST ) ) {
			die( '' );
		}

		// Get defaults.
		$success          = true;
		$message          = '';
		$is_network_admin = isset( $_REQUEST['is_network_admin'] ) && '1' === $_REQUEST['is_network_admin'];

		// Get options reference.
		$options      = Options::get_instance();
		$access_lists = Access_Lists::get_instance();

		// Get user list.
		$option               = 'access_users_approved';
		$admin_mode           = is_multisite() && $is_network_admin ? Helper::NETWORK_CONTEXT : Helper::SINGLE_CONTEXT;
		$auth_settings_option = $options->get( $option, $admin_mode, 'no override' );
		$auth_settings_option = is_array( $auth_settings_option ) ? $auth_settings_option : array();

		// Get multisite approved users (will be added to top of list, greyed out).
		$auth_override_multisite        = $options->get( 'advanced_override_multisite' );
		$auth_multisite_settings        = $options->get_all( Helper::NETWORK_CONTEXT );
		$auth_settings_option_multisite = array();
		if (
			is_multisite() &&
			! $is_network_admin &&
			1 !== intval( $auth_override_multisite ) &&
			array_key_exists( 'multisite_override', $auth_multisite_settings ) &&
			'1' === $auth_multisite_settings['multisite_override']
		) {
			$auth_settings_option_multisite = $options->get( $option, Helper::NETWORK_CONTEXT, 'allow override' );
			$auth_settings_option_multisite = is_array( $auth_settings_option_multisite ) ? $auth_settings_option_multisite : array();
			// Add multisite users to the beginning of the main user array.
			foreach ( array_reverse( $auth_settings_option_multisite ) as $approved_user ) {
				$approved_user['multisite_user'] = true;
				array_unshift( $auth_settings_option, $approved_user );
			}
		}

		// Get custom usermeta field to show.
		$advanced_usermeta = $options->get( 'advanced_usermeta' );

		// Filter user list to search terms.
		if ( ! empty( $_REQUEST['search'] ) ) {
			$search_term          = sanitize_text_field( wp_unslash( $_REQUEST['search'] ) );
			$auth_settings_option = array_filter(
				$auth_settings_option,
				function ( $user ) use ( $search_term ) {
					return stripos( $user['email'], $search_term ) !== false ||
					stripos( $user['role'], $search_term ) !== false ||
					stripos( $user['date_added'], $search_term ) !== false;
				}
			);
		}

		// Sort user list.
		$sort_by        = $options->get( 'advanced_users_sort_by', Helper::SINGLE_CONTEXT, 'allow override' ); // email, role, date_added (registered), created (date approved).
		$sort_order     = $options->get( 'advanced_users_sort_order', Helper::SINGLE_CONTEXT, 'allow override' ); // asc or desc.
		$sort_dimension = array();
		if ( in_array( $sort_by, array( 'email', 'role', 'date_added' ), true ) ) {
			foreach ( $auth_settings_option as $key => $user ) {
				if ( 'date_added' === $sort_by ) {
					$sort_dimension[ $key ] = date( 'Ymd', strtotime( $user[ $sort_by ] ) );
				} else {
					$sort_dimension[ $key ] = strtolower( $user[ $sort_by ] );
				}
			}
			$sort_order = 'asc' === $sort_order ? SORT_ASC : SORT_DESC;
			array_multisort( $sort_dimension, $sort_order, $auth_settings_option );
		} elseif ( 'created' === $sort_by && 'asc' !== $sort_order ) {
			// If default sort method and reverse order, just reverse the array.
			$auth_settings_option = array_reverse( $auth_settings_option );
		}

		// Ensure array keys run from 0..max (keys in database will be the original,
		// index, and removing users will not reorder the array keys of other users).
		$auth_settings_option = array_values( $auth_settings_option );

		// Get pager params.
		$total_users    = count( $auth_settings_option );
		$users_per_page = intval( $options->get( 'advanced_users_per_page', Helper::SINGLE_CONTEXT, 'allow override' ) );
		$current_page   = isset( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : 1;
		$total_pages    = ceil( $total_users / $users_per_page );
		if ( $total_pages < 1 ) {
			$total_pages = 1;
		}

		// Make sure current_page is between 1 and max pages.
		if ( $current_page < 1 ) {
			$current_page = 1;
		} elseif ( $current_page > $total_pages ) {
			$current_page = $total_pages;
		}

		// Render user list.
		ob_start();
		$offset = ( $current_page - 1 ) * $users_per_page;
		$max    = min( $offset + $users_per_page, count( $auth_settings_option ) );
		for ( $key = $offset; $key < $max; $key++ ) :
			$approved_user = $auth_settings_option[ $key ];
			if ( empty( $approved_user ) || count( $approved_user ) < 1 ) :
				continue;
			endif;
			$access_lists->render_user_element( $approved_user, $key, $option, $admin_mode, $advanced_usermeta );
		endfor;

		// Send response to client.
		$response = array(
			'success'          => $success,
			'message'          => $message,
			'html'             => ob_get_clean(),
			/* TRANSLATORS: %s: number of users */
			'total_users_html' => sprintf( _n( '%s user', '%s users', $total_users, 'authorizer' ), number_format_i18n( $total_users ) ),
			'total_pages_html' => number_format_i18n( $total_pages ),
			'total_pages'      => $total_pages,
		);
		header( 'content-type: application/json' );
		echo wp_json_encode( $response );
		exit;
	}


	/**
	 * Fired on a change event from the optional usermeta field in the approved
	 * user list. Updates the selected usermeta value, or saves it in the user's
	 * approved list entry if the user hasn't logged in yet and created a
	 * WordPress account.
	 *
	 * Action: wp_ajax_update_auth_usermeta
	 *
	 * @return void
	 */
	public function ajax_update_auth_usermeta() {
		// Fail silently if current user doesn't have permissions.
		if ( ! current_user_can( 'create_users' ) ) {
			die( '' );
		}

		// Nonce check.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'save_auth_settings' ) ) {
			die( '' );
		}

		// Fail if required post data doesn't exist.
		if ( ! isset( $_REQUEST['email'], $_REQUEST['usermeta'] ) ) {
			die( '' );
		}

		// Get values to update from post data.
		$options    = Options::get_instance();
		$email      = sanitize_email( wp_unslash( $_REQUEST['email'] ) );
		$meta_value = sanitize_meta( 'authorizer-usermeta', wp_unslash( $_REQUEST['usermeta'] ), 'user' );
		$meta_key   = $options->get( 'advanced_usermeta' );

		// If user doesn't exist, save usermeta selection to authorizer
		// list. This value will get saved to usermeta when the user first
		// logs in (i.e., when their WordPress account is created).
		$wp_user = get_user_by( 'email', $email );
		if ( ! $wp_user ) {
			// Look through multisite approved users and add a usermeta
			// reference for the current blog if the user is found.
			$auth_multisite_settings_access_users_approved               = is_multisite() ? get_blog_option( get_network()->blog_id, 'auth_multisite_settings_access_users_approved', array() ) : array();
			$should_update_auth_multisite_settings_access_users_approved = false;
			foreach ( $auth_multisite_settings_access_users_approved as $index => $approved_user ) {
				if ( 0 === strcasecmp( $email, $approved_user['email'] ) ) {
					if ( ! is_array( $auth_multisite_settings_access_users_approved[ $index ]['usermeta'] ) ) {
						// Initialize the array of usermeta for each blog this user belongs to.
						$auth_multisite_settings_access_users_approved[ $index ]['usermeta'] = array();
					} else {
						// There is already usermeta associated with this
						// preapproved user; iterate through it and make
						// sure it's not for old meta_keys (delete it if
						// so). This can happen if someone changes the
						// usermeta key in authorizer options, and we don't
						// want to hang on to old data.
						foreach ( $auth_multisite_settings_access_users_approved[ $index ]['usermeta'] as $blog_id => $usermeta ) {
							if ( array_key_exists( 'meta_key', $usermeta ) && $usermeta['meta_key'] === $meta_key ) {
								continue;
							} else {
								unset( $auth_multisite_settings_access_users_approved[ $index ]['usermeta'][ $blog_id ] );
							}
						}
					}
					$auth_multisite_settings_access_users_approved[ $index ]['usermeta'][ get_current_blog_id() ] = array(
						'meta_key'   => $meta_key,   // phpcs:ignore WordPress.DB.SlowDBQuery
						'meta_value' => $meta_value, // phpcs:ignore WordPress.DB.SlowDBQuery
					);
					$should_update_auth_multisite_settings_access_users_approved                                  = true;
				}
			}
			if ( $should_update_auth_multisite_settings_access_users_approved ) {
				update_blog_option( get_network()->blog_id, 'auth_multisite_settings_access_users_approved', $auth_multisite_settings_access_users_approved );
			}

			// Look through the approved users (of the current blog in a
			// multisite install, or just of the single site) and add a
			// usermeta reference if the user is found.
			$auth_settings_access_users_approved               = $options->get( 'access_users_approved', Helper::SINGLE_CONTEXT );
			$should_update_auth_settings_access_users_approved = false;
			foreach ( $auth_settings_access_users_approved as $index => $approved_user ) {
				if ( 0 === strcasecmp( $email, $approved_user['email'] ) ) {
					$auth_settings_access_users_approved[ $index ]['usermeta'] = array(
						'meta_key'   => $meta_key,   // phpcs:ignore WordPress.DB.SlowDBQuery
						'meta_value' => $meta_value, // phpcs:ignore WordPress.DB.SlowDBQuery
					);
					$should_update_auth_settings_access_users_approved         = true;
				}
			}
			if ( $should_update_auth_settings_access_users_approved ) {
				update_option( 'auth_settings_access_users_approved', $auth_settings_access_users_approved );
			}
		} else {
			// Update user's usermeta value for usermeta key stored in authorizer options.
			if ( strpos( $meta_key, 'acf___' ) === 0 && class_exists( 'acf' ) ) {
				// We have an ACF field value, so use the ACF function to update it.
				update_field( str_replace( 'acf___', '', $meta_key ), $meta_value, 'user_' . $wp_user->ID );
			} else {
				// We have a normal usermeta value, so just update it via the WordPress function.
				update_user_meta( $wp_user->ID, $meta_key, $meta_value );
			}
		}

		// Return 'success' value to AJAX call.
		die( 'success' );
	}


	/**
	 * Fired on a change event from the user fields in the user lists. Updates
	 * the selected user value.
	 *
	 * Action: wp_ajax_update_auth_user
	 *
	 * @return void
	 */
	public function ajax_update_auth_user() {
		// Fail silently if current user doesn't have permissions.
		if ( ! current_user_can( 'create_users' ) ) {
			die( '' );
		}

		// Nonce check.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'save_auth_settings' ) ) {
			die( '' );
		}

		// Fail if requesting a change to an invalid setting.
		if ( ! isset( $_POST['setting'] ) || ! in_array( wp_unslash( $_POST['setting'] ), array( 'access_users_pending', 'access_users_approved', 'access_users_blocked' ), true ) ) {
			die( '' );
		}

		// Get reference to plugin options.
		$options = Options::get_instance();

		// Track any emails that couldn't be added (used when adding users).
		$invalid_emails = array();

		// Editing a pending list entry.
		if ( 'access_users_pending' === $_POST['setting'] ) {
			// Sanitize posted data.
			$access_users_pending = array();
			if ( isset( $_POST['access_users_pending'] ) && is_array( $_POST['access_users_pending'] ) ) {
				$access_users_pending = $options->sanitize_update_auth_users( wp_unslash( $_POST['access_users_pending'] ) );
			}

			// Deal with each modified user (add or remove).
			foreach ( $access_users_pending as $pending_user ) {

				if ( 'add' === $pending_user['edit_action'] ) {

					// Add new user to pending list and save (skip if it's
					// already there--someone else might have just done it).
					if ( ! Authorization::get_instance()->is_email_in_list( $pending_user['email'], 'pending' ) ) {
						$auth_settings_access_users_pending = $options->sanitize_user_list(
							$options->get( 'access_users_pending', Helper::SINGLE_CONTEXT )
						);
						array_push( $auth_settings_access_users_pending, $pending_user );
						update_option( 'auth_settings_access_users_pending', $auth_settings_access_users_pending );
					}
				} elseif ( 'remove' === $pending_user['edit_action'] ) {

					// Remove user from pending list and save.
					$auth_settings_access_users_pending = $options->get( 'access_users_pending', Helper::SINGLE_CONTEXT );
					foreach ( $auth_settings_access_users_pending as $key => $existing_user ) {
						if ( 0 === strcasecmp( $pending_user['email'], $existing_user['email'] ) ) {
							unset( $auth_settings_access_users_pending[ $key ] );
							update_option( 'auth_settings_access_users_pending', $auth_settings_access_users_pending );
							break;
						}
					}
				}
			}
		}

		// Editing an approved list entry.
		if ( 'access_users_approved' === $_POST['setting'] ) {
			// Sanitize posted data.
			$access_users_approved = array();
			if ( isset( $_POST['access_users_approved'] ) && is_array( $_POST['access_users_approved'] ) ) {
				$access_users_approved = $options->sanitize_update_auth_users( wp_unslash( $_POST['access_users_approved'] ) );
			}

			// Deal with each modified user (add, remove, or change_role).
			foreach ( $access_users_approved as $approved_user ) {
				// Skip blank entries.
				if ( strlen( $approved_user['email'] ) < 1 ) {
					continue;
				}

				// New user (create user, or add existing user to current site in multisite).
				if ( 'add' === $approved_user['edit_action'] ) {
					$new_user = get_user_by( 'email', $approved_user['email'] );
					if ( false !== $new_user ) {
						// If we're adding an existing multisite user, make sure their
						// newly-assigned role is updated on all sites they are already in.
						if ( is_multisite() && 'false' !== $approved_user['multisite_user'] ) {
							foreach ( get_blogs_of_user( $new_user->ID ) as $blog ) {
								add_user_to_blog( $blog->userblog_id, $new_user->ID, $approved_user['role'] );
							}
						}
						// If this user already has an account on another site in the network, add them to this site.
						if ( is_multisite() ) {
							add_user_to_blog( get_current_blog_id(), $new_user->ID, $approved_user['role'] );
						}
					} elseif ( $approved_user['local_user'] && 'false' !== $approved_user['local_user'] ) {
						// Create a WP account for this new *local* user and email the password.
						$plaintext_password = wp_generate_password(); // random password
						// If there's already a user with this username (e.g.,
						// johndoe/johndoe@gmail.com exists, and we're trying to add
						// johndoe/johndoe@example.com), use the full email address
						// as the username.
						$username = explode( '@', $approved_user['email'] );
						$username = $username[0];
						if ( get_user_by( 'login', $username ) !== false ) {
							$username = Helper::lowercase( $approved_user['email'] );
						}
						if ( 'false' !== $approved_user['multisite_user'] ) {
							$result = wpmu_create_user(
								strtolower( $username ),
								$plaintext_password,
								Helper::lowercase( $approved_user['email'] )
							);
						} else {
							$result = wp_insert_user(
								array(
									'user_login'      => strtolower( $username ),
									'user_pass'       => $plaintext_password,
									'first_name'      => '',
									'last_name'       => '',
									'user_email'      => Helper::lowercase( $approved_user['email'] ),
									'user_registered' => date( 'Y-m-d H:i:s' ),
									'role'            => $approved_user['role'],
								)
							);
						}
						if ( ! is_wp_error( $result ) ) {
							// Email login credentials to new user.
							wp_new_user_notification( $result, null, 'both' );
						}
					}

					// Email new user welcome message if plugin option is set.
					Sync_Userdata::get_instance()->maybe_email_welcome_message( $approved_user['email'] );

					// Add new user to approved list and save (skip if it's
					// already there--someone else might have just done it).
					if ( 'false' !== $approved_user['multisite_user'] ) {
						if ( ! Authorization::get_instance()->is_email_in_list( $approved_user['email'], 'approved', 'multisite' ) ) {
							$auth_multisite_settings_access_users_approved = $options->sanitize_user_list(
								$options->get( 'access_users_approved', Helper::NETWORK_CONTEXT )
							);
							$approved_user['date_added']                   = date( 'M Y' );
							array_push( $auth_multisite_settings_access_users_approved, $approved_user );
							update_blog_option( get_network()->blog_id, 'auth_multisite_settings_access_users_approved', $auth_multisite_settings_access_users_approved );
						} else {
							$invalid_emails[] = $approved_user['email'];
						}
					} else {
						if ( ! Authorization::get_instance()->is_email_in_list( $approved_user['email'], 'approved' ) ) {
							$auth_settings_access_users_approved = $options->sanitize_user_list(
								$options->get( 'access_users_approved', Helper::SINGLE_CONTEXT )
							);
							$approved_user['date_added']         = date( 'M Y' );
							array_push( $auth_settings_access_users_approved, $approved_user );
							update_option( 'auth_settings_access_users_approved', $auth_settings_access_users_approved );
						} else {
							$invalid_emails[] = $approved_user['email'];
						}
					}

					// If we've added a new multisite user, go through all pending/approved/blocked lists
					// on individual sites and remove this user from them (to prevent duplicate entries).
					if ( 'false' !== $approved_user['multisite_user'] && is_multisite() ) {
						$list_names = array( 'access_users_pending', 'access_users_approved', 'access_users_blocked' );
						// phpcs:ignore WordPress.WP.DeprecatedFunctions.wp_get_sitesFound
						$sites = function_exists( 'get_sites' ) ? get_sites() : wp_get_sites( array( 'limit' => PHP_INT_MAX ) );
						foreach ( $sites as $site ) {
							$blog_id = function_exists( 'get_sites' ) ? $site->blog_id : $site['blog_id'];
							foreach ( $list_names as $list_name ) {
								$user_list    = get_blog_option( $blog_id, 'auth_settings_' . $list_name, array() );
								$list_changed = false;
								foreach ( $user_list as $key => $user ) {
									if ( 0 === strcasecmp( $user['email'], $approved_user['email'] ) ) {
										unset( $user_list[ $key ] );
										$list_changed = true;
									}
								}
								if ( $list_changed ) {
									update_blog_option( $blog_id, 'auth_settings_' . $list_name, $user_list );
								}
							}
						}
					}
				} elseif ( 'remove' === $approved_user['edit_action'] ) { // Remove user from approved list and save (also remove their role if they have a WordPress account).
					if ( 'false' !== $approved_user['multisite_user'] ) {
						$auth_multisite_settings_access_users_approved = $options->get( 'access_users_approved', Helper::NETWORK_CONTEXT );
						foreach ( $auth_multisite_settings_access_users_approved as $key => $existing_user ) {
							if ( 0 === strcasecmp( $approved_user['email'], $existing_user['email'] ) ) {
								// Remove role of the associated WordPress user from all blogs (but don't delete the user).
								$user = get_user_by( 'email', $approved_user['email'] );
								if ( false !== $user ) {
									// Loop through all of the blogs this user is a member of and remove their capabilities.
									foreach ( get_blogs_of_user( $user->ID ) as $blog ) {
										remove_user_from_blog( $user->ID, $blog->userblog_id, '' );
									}
								}
								// Remove entry from Approved Users list.
								unset( $auth_multisite_settings_access_users_approved[ $key ] );
								update_blog_option( get_network()->blog_id, 'auth_multisite_settings_access_users_approved', $auth_multisite_settings_access_users_approved );
								break;
							}
						}
					} else {
						$auth_settings_access_users_approved = $options->get( 'access_users_approved', Helper::SINGLE_CONTEXT );
						foreach ( $auth_settings_access_users_approved as $key => $existing_user ) {
							if ( 0 === strcasecmp( $approved_user['email'], $existing_user['email'] ) ) {
								// Remove role of the associated WordPress user (but don't delete the user).
								$user = get_user_by( 'email', $approved_user['email'] );
								if ( false !== $user ) {
									$user->set_role( '' );
								}
								// Remove entry from Approved Users list.
								unset( $auth_settings_access_users_approved[ $key ] );
								update_option( 'auth_settings_access_users_approved', $auth_settings_access_users_approved );
								break;
							}
						}
					}
				} elseif ( 'change_role' === $approved_user['edit_action'] ) { // Update user's role in WordPress.
					$changed_user = get_user_by( 'email', $approved_user['email'] );
					if ( $changed_user ) {
						if ( is_multisite() && 'false' !== $approved_user['multisite_user'] ) {
							foreach ( get_blogs_of_user( $changed_user->ID ) as $blog ) {
								add_user_to_blog( $blog->userblog_id, $changed_user->ID, $approved_user['role'] );
							}
						} else {
							$changed_user->set_role( $approved_user['role'] );
						}
					}

					if ( 'false' !== $approved_user['multisite_user'] ) {
						if ( Authorization::get_instance()->is_email_in_list( $approved_user['email'], 'approved', 'multisite' ) ) {
							$auth_multisite_settings_access_users_approved = $options->sanitize_user_list(
								$options->get( 'access_users_approved', Helper::NETWORK_CONTEXT )
							);
							foreach ( $auth_multisite_settings_access_users_approved as $key => $existing_user ) {
								if ( 0 === strcasecmp( $approved_user['email'], $existing_user['email'] ) ) {
									$auth_multisite_settings_access_users_approved[ $key ]['role'] = $approved_user['role'];
									break;
								}
							}
							update_blog_option( get_network()->blog_id, 'auth_multisite_settings_access_users_approved', $auth_multisite_settings_access_users_approved );
						}
					} else {
						// Update user's role in approved list and save.
						if ( Authorization::get_instance()->is_email_in_list( $approved_user['email'], 'approved' ) ) {
							$auth_settings_access_users_approved = $options->sanitize_user_list(
								$options->get( 'access_users_approved', Helper::SINGLE_CONTEXT )
							);
							foreach ( $auth_settings_access_users_approved as $key => $existing_user ) {
								if ( 0 === strcasecmp( $approved_user['email'], $existing_user['email'] ) ) {
									$auth_settings_access_users_approved[ $key ]['role'] = $approved_user['role'];
									break;
								}
							}
							update_option( 'auth_settings_access_users_approved', $auth_settings_access_users_approved );
						}
					}
				}
			}
		}

		// Editing a blocked list entry.
		if ( 'access_users_blocked' === $_POST['setting'] ) {
			// Sanitize post data.
			$access_users_blocked = array();
			if ( isset( $_POST['access_users_blocked'] ) && is_array( $_POST['access_users_blocked'] ) ) {
				$access_users_blocked = $options->sanitize_update_auth_users(
					wp_unslash( $_POST['access_users_blocked'] ),
					array(
						'allow_wildcard_email' => true,
					)
				);
			}

			// Deal with each modified user (add or remove).
			foreach ( $access_users_blocked as $blocked_user ) {

				if ( 'add' === $blocked_user['edit_action'] ) {

					// Add auth_blocked usermeta for the user.
					$blocked_wp_user = get_user_by( 'email', $blocked_user['email'] );
					if ( false !== $blocked_wp_user ) {
						update_user_meta( $blocked_wp_user->ID, 'auth_blocked', 'yes' );
					}

					// Add new user to blocked list and save (skip if it's
					// already there--someone else might have just done it).
					if ( ! Authorization::get_instance()->is_email_in_list( $blocked_user['email'], 'blocked' ) ) {
						$auth_settings_access_users_blocked = $options->sanitize_user_list(
							$options->get( 'access_users_blocked', Helper::SINGLE_CONTEXT )
						);
						$blocked_user['date_added']         = date( 'M Y' );
						array_push( $auth_settings_access_users_blocked, $blocked_user );
						update_option( 'auth_settings_access_users_blocked', $auth_settings_access_users_blocked );
					} else {
						$invalid_emails[] = $blocked_user['email'];
					}
				} elseif ( 'remove' === $blocked_user['edit_action'] ) {

					// Remove auth_blocked usermeta for the user.
					$unblocked_user = get_user_by( 'email', $blocked_user['email'] );
					if ( false !== $unblocked_user ) {
						delete_user_meta( $unblocked_user->ID, 'auth_blocked', 'yes' );
					}

					// Remove user from blocked list and save.
					$auth_settings_access_users_blocked = $options->get( 'access_users_blocked', Helper::SINGLE_CONTEXT );
					foreach ( $auth_settings_access_users_blocked as $key => $existing_user ) {
						if ( 0 === strcasecmp( $blocked_user['email'], $existing_user['email'] ) ) {
							unset( $auth_settings_access_users_blocked[ $key ] );
							update_option( 'auth_settings_access_users_blocked', $auth_settings_access_users_blocked );
							break;
						}
					}
				}
			}
		}

		// Send response to client.
		$response = array(
			'success'        => true,
			'invalid_emails' => $invalid_emails,
		);
		header( 'content-type: application/json' );
		echo wp_json_encode( $response );
		exit;
	}

}
