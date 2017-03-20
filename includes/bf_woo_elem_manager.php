<?php
	/**
	 * Created by PhpStorm.
	 * User: Victor
	 * Date: 20/03/2017
	 * Time: 15:06
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class bf_woo_elem_manager {

		public static $fields_loaded = array();
		protected static $version;
		private static $plugin_slug = 'buddyforms-woocommerce-form-elements';
		private $fields = array();

		public function __construct() {
			self::$version = self::$version = '1.0.0';
			self::load_plugins_dependency();
			try {
				if ( self::is_buddyForms_active() ) {
					if ( bf_woo_elem_fs::getFreemius()->is_paying() ) {
						require_once 'FormidableAutoCompleteAdmin.php';

					}
				}
			} catch ( Exception $ex ) {

			}

		}

		public static function load_plugins_dependency() {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		public static function is_buddyForms_active() {
			self::load_plugins_dependency();

			return is_plugin_active( 'buddyforms-premium/BuddyForms.php' );
		}
	}