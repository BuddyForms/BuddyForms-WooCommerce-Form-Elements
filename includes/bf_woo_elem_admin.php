<?php
/**
 * @package    WordPress
 * @subpackage Woocommerce, BuddyForms
 * @author     ThemKraft Dev Team
 * @copyright  2017, Themekraft
 * @link       http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license    GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bf_woo_elem_admin {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'bf_wc_admin_enqueue_script' ) );
	}

	/**
	 * Load script only in the buddy form page
	 *
	 * @param $hook_suffix
	 */
	public function bf_wc_admin_enqueue_script( $hook_suffix ) {
		global $post;

		if ( ( isset( $post ) && $post->post_type == 'buddyforms' && isset( $_GET['action'] ) && $_GET['action'] == 'edit'
		       || isset( $post ) && $post->post_type == 'buddyforms' && $hook_suffix == 'post-new.php' )
		     || $hook_suffix == 'buddyforms_page_create-new-form' || $hook_suffix == 'buddyforms_page_bf_add_ons'
		) {
			wp_enqueue_script( 'buddyforms-woocommerce', BF_WOO_ELEM_JS_PATH . 'buddyforms-woocommerce.js', array( 'jquery' ) );
		}
	}
}