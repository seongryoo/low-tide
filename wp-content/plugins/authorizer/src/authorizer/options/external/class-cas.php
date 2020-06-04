<?php
/**
 * Authorizer
 *
 * @license  GPL-2.0+
 * @link     https://github.com/uhm-coe/authorizer
 * @package  authorizer
 */

namespace Authorizer\Options\External;

use Authorizer\Helper;
use Authorizer\Options;

/**
 * Contains functions for rendering the CAS options in the External Service
 * tab in Authorizer Settings.
 */
class Cas extends \Authorizer\Static_Instance {

	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_checkbox_auth_external_cas( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Make sure php5-curl extension is installed on server.
		$curl_installed_message = ! function_exists( 'curl_init' ) ? __( '<a href="http://www.php.net//manual/en/curl.installation.php" target="_blank" style="color: #dc3232;">PHP CURL extension</a> is not installed', 'authorizer' ) : '';

		// Make sure php_openssl extension is installed on server.
		$openssl_installed_message = ! extension_loaded( 'openssl' ) ? __( '<a href="http://stackoverflow.com/questions/23424459/enable-php-openssl-not-working" target="_blank" style="color: #dc3232;">PHP openssl extension</a> is not installed', 'authorizer' ) : '';

		// Build error message string.
		$error_message = '';
		if ( strlen( $curl_installed_message ) > 0 || strlen( $openssl_installed_message ) > 0 ) {
			$error_message = '<span style="color: #dc3232;">(' .
				__( 'Warning', 'authorizer' ) . ': ' .
				$curl_installed_message .
				( strlen( $curl_installed_message ) > 0 && strlen( $openssl_installed_message ) > 0 ? '; ' : '' ) .
				$openssl_installed_message .
				')</span>';
		}

		// Print option elements.
		?>
		<input type="checkbox" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="1"<?php checked( 1 === intval( $auth_settings_option ) ); ?> /><label for="auth_settings_<?php echo esc_attr( $option ); ?>"><?php esc_html_e( 'Enable CAS Logins', 'authorizer' ); ?></label> <?php echo wp_kses( $error_message, Helper::$allowed_html ); ?>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_custom_label( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_custom_label';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		esc_html_e( 'The button on the login page will read:', 'authorizer' );
		?>
		<p><a class="button-primary button-large button-external"><span class="dashicons dashicons-lock"></span> <strong><?php esc_html_e( 'Sign in with', 'authorizer' ); ?> </strong><input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="CAS" /></a></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_host( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_host';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" />
		<p class="description"><?php esc_html_e( 'Example:  authn.example.edu', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_port( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_port';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" style="width:50px;" />
		<p class="description"><?php esc_html_e( 'Example:  443', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_path( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_path';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" />
		<p class="description"><?php esc_html_e( 'Example:  /cas', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_select_cas_version( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_version';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<select id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]">
			<option value="SAML_VERSION_1_1" <?php selected( $auth_settings_option, 'SAML_VERSION_1_1' ); ?>>SAML_VERSION_1_1</option>
			<option value="CAS_VERSION_3_0" <?php selected( $auth_settings_option, 'CAS_VERSION_3_0' ); ?>>CAS_VERSION_3_0</option>
			<option value="CAS_VERSION_2_0" <?php selected( $auth_settings_option, 'CAS_VERSION_2_0' ); ?>>CAS_VERSION_2_0</option>
			<option value="CAS_VERSION_1_0" <?php selected( $auth_settings_option, 'CAS_VERSION_1_0' ); ?>>CAS_VERSION_1_0</option>
		</select>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_attr_email( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_attr_email';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" />
		<p class="description">
			<?php esc_html_e( 'Example:  mail', 'authorizer' ); ?>
			<br>
			<small><?php echo wp_kses( __( "Note: If your CAS server doesn't return an attribute containing an email, you can specify the @domain portion of the email address here, and the email address will be constructed from it and the username. For example, if user 'bob' logs in and his email address should be bob@example.edu, then enter <strong>@example.edu</strong> in this field.", 'authorizer' ), Helper::$allowed_html ); ?></small>
		</p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_attr_first_name( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_attr_first_name';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" />
		<p class="description"><?php esc_html_e( 'Example:  givenName', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_cas_attr_last_name( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_attr_last_name';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" />
		<p class="description"><?php esc_html_e( 'Example:  sn', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_checkbox_cas_attr_update_on_login( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_attr_update_on_login';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="checkbox" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="1"<?php checked( 1 === intval( $auth_settings_option ) ); ?> /><label for="auth_settings_<?php echo esc_attr( $option ); ?>"><?php esc_html_e( 'Update first and last name fields on login (will overwrite any name the user has supplied in their profile)', 'authorizer' ); ?></label>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_checkbox_cas_auto_login( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_auto_login';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="checkbox" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="1"<?php checked( 1 === intval( $auth_settings_option ) ); ?> /><label for="auth_settings_<?php echo esc_attr( $option ); ?>"><?php esc_html_e( "Immediately redirect to CAS login form if it's the only enabled external service and WordPress logins are hidden", 'authorizer' ); ?></label>
		<p class="description"><?php esc_html_e( 'Note: This feature will only work if you have checked "Hide WordPress Logins" in Advanced settings, and if CAS is the only enabled service (i.e., no Google or LDAP). If you have enabled CAS Single Sign-On (SSO), and a user has already logged into CAS elsewhere, enabling this feature will allow automatic logins without any user interaction.', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_checkbox_cas_link_on_username( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'cas_link_on_username';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="checkbox" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="1"<?php checked( 1 === intval( $auth_settings_option ) ); ?> /><label for="auth_settings_<?php echo esc_attr( $option ); ?>"><?php esc_html_e( 'Link CAS accounts to WordPress accounts by their username (leave this off to link by email address)', 'authorizer' ); ?></label>
		<p class="description"><?php esc_html_e( "Note: The default (and most secure) behavior is to associate WordPress accounts with CAS accounts by the email they have in common. However, some uncommon CAS server configurations don't contain email addresses for users. Enable this option if your CAS server doesn't have an attribute containing an email, or if you have WordPress accounts that don't have emails.", 'authorizer' ); ?></p>
		<?php
	}

}
