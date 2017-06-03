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
	
	protected static $version = '1.3.5.1';
	private static $plugin_slug = 'bf_woo_elem';
	
	public function __construct() {
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_log.php';
		new bf_woo_elem_log();
		try {
			$this->bf_wc_fe_includes();
		} catch ( Exception $ex ) {
			bf_woo_elem_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => bf_woo_elem_manager::get_slug(),
				'object_subtype' => 'loading_dependency',
				'object_name'    => $ex->getMessage(),
			) );
			
		}
	}
	
	public function bf_wc_fe_includes() {
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_form_builder.php';
		new bf_woo_elem_form_builder();
		require_once WC()->plugin_path() . '/includes/admin/class-wc-admin-post-types.php';
		require_once WC()->plugin_path() . '/includes/admin/meta-boxes/class-wc-meta-box-product-data.php';
		require_once WC()->plugin_path() . '/includes/admin/meta-boxes/class-wc-meta-box-product-images.php';
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_form_elements.php';
		new bf_woo_elem_form_element();
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_form_elements_save.php';
		new bf_woo_elem_form_elements_save();
		
		if ( ! function_exists( 'woocommerce_wp_text_input' ) ) {
			include_once( WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php' );
		}
	}
	
	public static function get_slug() {
		return self::$plugin_slug;
	}
	
	static function get_version() {
		return self::$version;
	}
	
	/**
	 * @return array
	 */
	public static function get_unhandled_tabs() {
		$unhandled = array();
		if ( class_exists( 'WC_Vendors' ) ) {
			$unhandled['commission'] = array( 'label' => 'WC Vendors' );
		}
		
		return apply_filters( 'bf_woo_element_woo_unhandled_tabs', $unhandled );
	}
}