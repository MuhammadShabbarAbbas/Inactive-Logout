<?php
//Not Permission to agree more or less then given
if( !defined('ABSPATH') ) {
	die( '-1' );
}

/**
 * Core Functions
 *
 * @since  1.0.0
 * @author  Deepen
 */
class Inactive__Logout_functions {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'ina_adding_meta_tag') );
		add_action( 'admin_head', array( $this, 'ina_adding_meta_tag') );
		add_action( 'wp_footer', array( $this, 'ina_logout_dialog_modal') );
		add_action( 'admin_footer', array( $this, 'ina_logout_dialog_modal') );

		//Ajax for checking last session
		add_action( 'wp_ajax_ina_checklastSession', array( $this, 'ina_checking_last_session' ) );
		add_action( 'wp_ajax_nopriv_ina_checklastSession', array( $this, 'ina_checking_last_session' ) );
	}

	/**
	* Check Last Session and Logout User
	*/
	public function ina_checking_last_session() {
		check_ajax_referer( '_checklastSession', 'security' );
		$timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : NULL;
		$do = $_POST['do'];
		$current_time = time();
		if( is_user_logged_in() ) {
			switch($do) {
				case 'ina_updateLastSession':
				update_user_meta( get_current_user_id(), '__ina_last_active_session', $timestamp );
				break;

				case 'ina_logout':
				//Logout Current Users
				wp_logout();
				echo json_encode( array( 'msg' => __('You have been logged out because of inactivity.', 'ina-logout') ) );
				break;

				default:
				break;
			}
		}
		
		wp_die();
	}

	/**
	* Add a Timeout Defined Meta tag for JS
	*/
	public function ina_adding_meta_tag() {
		require_once INACTIVE_LOGOUT_VIEWS . '/add-meta.php';
	}

	/**
	* Adding Dialog in footer
	*/
	public function ina_logout_dialog_modal() {
		require_once INACTIVE_LOGOUT_VIEWS . '/tpl-inactive-logout-dialog.php';
	}

}
new Inactive__Logout_functions();

