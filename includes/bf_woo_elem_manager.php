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
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_log.php';
		try {
			require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_admin.php';
			new bf_woo_elem_admin();

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
		require_once WC()->plugin_path() . '/includes/admin/meta-boxes/class-wc-meta-box-product-data.php';
		require_once WC()->plugin_path() . '/includes/admin/meta-boxes/class-wc-meta-box-product-images.php';
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_form_elements.php';
		new bf_woo_elem_form_element();
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_form_elements_save.php';
		new bf_woo_elem_form_elements_save();

		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_attribute.php' );
		//	new bf_woo_elem_product_attribute();
		//include_once(BF_WOO_ELEM_INCLUDES_PATH . '/orm-elements/bf-wc-product-variations.php');
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_downloadable.php' );
		//new bf_woo_elem_product_downloadable();
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_general.php' );
		//new bf_woo_elem_product_general();
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_inventory.php' );
		//new bf_woo_elem_product_inventory();
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_linked.php' );
		//new bf_woo_elem_product_linked();
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_shipping.php' );
		//new bf_woo_elem_product_shipping();
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'form-elements/bf_woo_elem_product_type.php' );
		//new bf_woo_elem_product_type();
		include_once( BF_WOO_ELEM_INCLUDES_PATH . 'wc-admin-assets-frontend/class-wc-admin-assets-frontend.php' );

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

	public static function load_field_template( $part ) {
		$template = locate_template( array( 'templates/' . $part . '.php' ) );
		if ( ! $template ) {
			return BF_WOO_ELEM_TEMPLATES_PATH . $part . ".php";
		} else {
			return $template;
		}
	}
}