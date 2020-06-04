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
 * Contains functions for rendering the Google options in the External Service
 * tab in Authorizer Settings.
 */
class Google extends \Authorizer\Static_Instance {

	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_checkbox_auth_external_google( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'google';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="checkbox" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="1"<?php checked( 1 === intval( $auth_settings_option ) ); ?> /><label for="auth_settings_<?php echo esc_attr( $option ); ?>"><?php esc_html_e( 'Enable Google Logins', 'authorizer' ); ?></label>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_google_clientid( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'google_clientid';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		$site_url_parts = wp_parse_url( get_site_url() );
		$site_url_host  = $site_url_parts['scheme'] . '://' . $site_url_parts['host'] . '/';

		esc_html_e( "If you don't have a Google Client ID and Secret, generate them by following these instructions:", 'authorizer' );
		?>
		<ol>
			<li><?php echo wp_kses( __( 'Click <strong>Create a Project</strong> on the <a href="https://cloud.google.com/console" target="_blank">Google Developers Console</a>. You can name it whatever you want.', 'authorizer' ), Helper::$allowed_html ); ?></li>
			<li><?php echo wp_kses( __( 'Within the project, navigate to <em>APIs and Auth</em> &gt; <em>Credentials</em>, then click <strong>Create New Client ID</strong> under OAuth. Use these settings:', 'authorizer' ), Helper::$allowed_html ); ?>
				<ul>
					<li><?php echo wp_kses( __( 'Application Type: <strong>Web application</strong>', 'authorizer' ), Helper::$allowed_html ); ?></li>
					<li><?php esc_html_e( 'Authorized Javascript Origins:', 'authorizer' ); ?> <strong><?php echo esc_html( rtrim( $site_url_host, '/' ) ); ?></strong></li>
					<li><?php echo wp_kses( __( 'Authorized Redirect URI: <em>none</em>', 'authorizer' ), Helper::$allowed_html ); ?></li>
				</ul>
			</li>
			<li><?php esc_html_e( 'Copy/paste your new Client ID/Secret pair into the fields below.', 'authorizer' ); ?></li>
			<li><?php echo wp_kses( __( '<strong>Note</strong>: Navigate to <em>APIs and Auth</em> &gt; <em>Consent screen</em> to change the way the Google consent screen appears after a user has successfully entered their password, but before they are redirected back to WordPress.', 'authorizer' ), Helper::$allowed_html ); ?></li>
			<li><?php echo wp_kses( __( 'Note: Google may have a more recent version of these instructions in their <a href="https://developers.google.com/identity/sign-in/web/devconsole-project" target="_blank">developer documentation</a>.', 'authorizer' ), Helper::$allowed_html ); ?></li>
		</ol>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" />
		<p class="description"><?php esc_html_e( 'Example:  1234567890123-kdjr85yt6vjr6d8g7dhr8g7d6durjf7g.apps.googleusercontent.com', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_google_clientsecret( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'google_clientsecret';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<input type="text" id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $auth_settings_option ); ?>" placeholder="" style="width:220px;" />
		<p class="description"><?php esc_html_e( 'Example:  sDNgX5_pr_5bly-frKmvp8jT', 'authorizer' ); ?></p>
		<?php
	}


	/**
	 * Settings print callback.
	 *
	 * @param  string $args Args (e.g., multisite admin mode).
	 * @return void
	 */
	public function print_text_google_hosteddomain( $args = '' ) {
		// Get plugin option.
		$options              = Options::get_instance();
		$option               = 'google_hosteddomain';
		$auth_settings_option = $options->get( $option, Helper::get_context( $args ), 'allow override', 'print overlay' );

		// Print option elements.
		?>
		<textarea id="auth_settings_<?php echo esc_attr( $option ); ?>" name="auth_settings[<?php echo esc_attr( $option ); ?>]" placeholder="" style="width:220px;"><?php echo esc_html( $auth_settings_option ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Restrict Google logins to a specific Google Apps hosted domain (for example, mycollege.edu). Leave blank to allow all Google sign-ins.', 'authorizer' ); ?> <?php esc_html_e( 'If restricting to multiple domains, add one domain per line.', 'authorizer' ); ?></p>
		<?php
	}

}
