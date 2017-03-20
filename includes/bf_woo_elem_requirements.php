<?php

	/**
	 * Created by PhpStorm.
	 * User: Victor
	 * Date: 20/03/2017
	 * Time: 14:36
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class bf_woo_elem_requirements {
		public function __construct() {
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

		public function setup_init() {
			// Only Check for requirements in the admin
			if ( ! is_admin() ) {
				return;
			}
			add_action( 'tgmpa_register', array( $this, 'setup_and_check' ) );
			//add_action( 'in_admin_footer', array( $this, 'remove_woo_footer' ) );
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
				'id'           => 'buddyforms-tgmpa',
				// Unique ID for hashing notices for multiple instances of TGMPA.
				'parent_slug'  => 'plugins.php',
				// Parent menu slug.
				'capability'   => 'manage_options',
				// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,
				// Show admin notices or not.
				'dismissable'  => false,
				// If false, a user cannot dismiss the nag message.
				'is_automatic' => true,
				// Automatically activate plugins after installation or not.
			);

			// Call the tgmpa function to register the required plugins
			tgmpa( $plugins, $config );
		}


	}