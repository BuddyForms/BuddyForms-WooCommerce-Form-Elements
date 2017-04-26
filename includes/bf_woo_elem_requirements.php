<?php
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bf_woo_elem_requirements {
	
	public function __construct() {
		require_once BF_WOO_ELEM_INCLUDES_PATH . 'resources/tgm/class-tgm-plugin-activation.php';
		
		add_action( 'init', array( $this, 'setup_init' ), 1, 1 );
	}
	
	public static function is_woocommerce_active() {
		self::load_plugins_dependency();
		
		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}
	
	public static function load_plugins_dependency() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	
	public static function is_buddypress_active() {
		self::load_plugins_dependency();
		
		return is_plugin_active( 'buddypress/bp-loader.php' );
	}
	
	public static function is_buddy_form_active() {
		self::load_plugins_dependency();
		
		return ( is_plugin_active( 'buddyforms-premium/BuddyForms.php' ) || is_plugin_active( 'buddyforms/BuddyForms.php' ) );
	}
	
	public function setup_init() {
		// Only Check for requirements in the admin
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'tgmpa_register', array( $this, 'setup_and_check' ) );
		add_action( 'in_admin_footer', array( $this, 'remove_woo_footer' ) );
	}
	
	public function remove_woo_footer() {
		$current_screen = get_current_screen();
		if ( isset( $current_screen->id ) && $current_screen->id == 'admin_page_bf_wc_fe-install-plugins' && class_exists( 'WC_Admin' ) ) {
			$this->remove_anonymous_callback_hook( 'admin_footer_text', 'WC_Admin', 'admin_footer_text' );
		}
	}
	
	private function remove_anonymous_callback_hook( $tag, $class, $method ) {
		$filters = $GLOBALS['wp_filter'][ $tag ];
		
		if ( empty ( $filters ) || empty( $filters->callbacks ) ) {
			return;
		}
		
		foreach ( $filters->callbacks as $priority => $filter ) {
			foreach ( $filter as $identifier => $function ) {
				if ( is_array( $function ) && is_a( $function['function'][0], $class ) && $method === $function['function'][1] ) {
					remove_filter( $tag, array( $function['function'][0], $method ), $priority );
				}
			}
		}
	}
	
	public function setup_and_check() {
		// Create the required plugins array
		$plugins['woocommerce'] = array(
			'name'     => 'WooCommerce',
			'slug'     => 'woocommerce',
			'required' => true,
		);
		
		if ( ! defined( 'BUDDYFORMS_PRO_VERSION' ) ) {
			$plugins['buddyforms'] = array(
				'name'     => 'BuddyForms',
				'slug'     => 'buddyforms',
				'required' => true,
			);
		}
		
		$config = array(
			'id'           => 'bf_wc_fe',
			'menu'         => 'bf_wc_fe-install-plugins', // Menu slug.
			'parent_slug'  => 'plugins.php', // Parent menu slug.
			'capability'   => 'manage_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true, // Show admin notices or not.
			'dismissable'  => false, // If false, a user cannot dismiss the nag message.
			'is_automatic' => true, // Automatically activate plugins after installation or not.
		);
		
		// Call the tgmpa function to register the required plugins
		tgmpa( $plugins, $config );
	}
}