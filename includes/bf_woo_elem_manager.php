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

class bf_woo_elem_manager {
	
	protected static $version = '1.0.0';
	private static $plugin_slug = 'bf_woo_elem';
	
	public function __construct() {
		try {
			require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_admin.php';
			new bf_woo_elem_admin();
			
			$this->bf_wc_fe_includes();
			
		} catch ( Exception $ex ) {
			
		}
	}
	
	public function bf_wc_fe_includes() {
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'form-builder-elements.php';
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'class-wc-meta-box-product-data.php';
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'class-wc-meta-box-product-images.php';
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'form-elements.php';
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'form-elements-save.php';
		
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-attribute.php' );
		//include_once(BF_WOO_ELEM_INCLUDES_PATH . '/orm-elements/bf-wc-product-variations.php');
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-downloadable.php' );
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-general.php' );
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-inventory.php' );
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-linked.php' );
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-shipping.php' );
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf-wc-product-type.php' );
		
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'wc-admin-assets-frontend/class-wc-admin-assets-frontend.php' );
		
		if ( ! function_exists( 'woocommerce_wp_text_input' ) ) {
			include_once( WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php' );
		}
	}
}