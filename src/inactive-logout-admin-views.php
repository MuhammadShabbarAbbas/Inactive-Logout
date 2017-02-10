<?php
//Not Permission to agree more or less then given
if( !defined('ABSPATH') ) {
	die( '-1' );
}

/**
 * Admin Viws Class
 *
 * @since  1.0.0
 * @author  Deepen
 */
class Inactive__Logout_adminViews {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ina_create_options_menu' ) );
	}

	/**
	 * Add a Menu Option in settings
	 */
	public function ina_create_options_menu() {
		add_options_page(
			__("Inactive User Logout Settings", "ina-logout"),
			__("Inactive Logout", "ina-logout"),
			'manage_options',
			'inactive-logout',
			array( $this, 'ina__render_options' )
			);
	}

	/** Rendering the output */
	public function ina__render_options() {
		if( isset($_POST['submit']) && ! wp_verify_nonce( $_POST['_save_timeout_settings'], '_nonce_action_save_timeout_settings' ) ) {
			wp_die("Not Allowed");
			exit;
		}

		$saved = false;
		if( isset($_POST['submit']) ) {
			$idle_timeout = filter_input( INPUT_POST, 'idle_timeout', FILTER_SANITIZE_NUMBER_INT );
			$idle_timeout_message = wp_kses_post( filter_input(INPUT_POST, 'idle_message_text') );
			$idle_disable_countdown = filter_input(INPUT_POST, 'idle_disable_countdown', FILTER_SANITIZE_NUMBER_INT);
			$ina_show_warn_message_only = filter_input(INPUT_POST, 'ina_show_warn_message_only', FILTER_SANITIZE_NUMBER_INT);
			$ina_show_warn_message = wp_kses_post( filter_input(INPUT_POST, 'ina_show_warn_message') );
			$ina_disable_multiple_login = filter_input(INPUT_POST, 'ina_disable_multiple_login', FILTER_SANITIZE_NUMBER_INT);

			$ina_background_popup = trim( filter_input( INPUT_POST, 'ina_color_picker' ) );
			$ina_background_popup = strip_tags( stripslashes( $ina_background_popup ) );

			$ina_full_overlay = filter_input(INPUT_POST, 'ina_full_overlay', FILTER_SANITIZE_NUMBER_INT);
			$ina_enable_redirect_link = filter_input(INPUT_POST, 'ina_enable_redirect_link', FILTER_SANITIZE_NUMBER_INT);
			$ina_redirect_page = filter_input(INPUT_POST, 'ina_redirect_page');

			$save_minutes = $idle_timeout * 60; //Minutes
			if($idle_timeout) {
				update_option( '__ina_logout_time', $save_minutes );
				update_option( '__ina_logout_message', $idle_timeout_message );
				update_option( '__ina_disable_countdown', $idle_disable_countdown );
				update_option( '__ina_warn_message_enabled', $ina_show_warn_message_only );
				update_option( '__ina_warn_message', $ina_show_warn_message );
				update_option( '__ina_concurrent_login', $ina_disable_multiple_login );
				update_option( '__ina_full_overlay', $ina_full_overlay );
				update_option( '__ina_popup_overlay_color', $ina_background_popup );
				update_option( '__ina_enable_redirect', $ina_enable_redirect_link );
				update_option( '__ina_redirect_page_link', $ina_redirect_page );

				$saved = true;
				$helper = Inactive__logout__Helpers::instance();
				$helper->ina_reload();
			}
		}

		$time = get_option( '__ina_logout_time' );
		$countdown_enable = get_option( '__ina_disable_countdown' );
		$ina_warn_message_enabled = get_option( '__ina_warn_message_enabled' );
		$ina_concurrent = get_option( '__ina_concurrent_login' );
		$ina_full_overlay = get_option( '__ina_full_overlay' );
		$ina_popup_overlay_color = get_option( '__ina_popup_overlay_color' );
		$ina_enable_redirect = get_option( '__ina_enable_redirect' );
		$ina_redirect_page_link = get_option( '__ina_redirect_page_link' );

		// Css rules for Color Picker
		wp_enqueue_style( 'wp-color-picker' );

		//Include Template
		require_once INACTIVE_LOGOUT_VIEWS . '/tpl-inactive-logout-settings.php';
	}

}
new Inactive__Logout_adminViews();
