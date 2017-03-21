<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'loaderNew' ) ) {
	
	class loaderNew {
		/**
		 * Instace of this class
		 * @var instance
		 */
		protected static $instance = null;
		
		private function __construct() {
			$this->constants();
			$this->bf_wc_fe_loader();
			
			require_once GFIREM_CLASSES_PATH . 'bf_woo_elem_requirements.php';
			$this->requirements = new bf_woo_elem_requirements();
			if ( $this->requirements->satisfied() ) {
				require_once GFIREM_CLASSES_PATH . 'bf_woo_elem_manager.php';
				new bf_woo_elem_manager();
			} else {
				
			}
		}
		
		function bf_wc_fe_loader() {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'bf_wc_admin_enqueue_script' ), 1 );
				bf_wc_fe_includes();
			}
		}
		
		private function constants() {
			define( 'BF_WOO_ELEM_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'BF_WOO_ELEM_BASE_NAMEBASE_FILE', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) . 'bf_woo_elem_loader.php' );
			define( 'BF_WOO_ELEM_URL_PATH', plugin_dir_url( __FILE__ ) );
			define( 'BF_WOO_ELEM_CSS_PATH', GFIREM_URL_PATH . 'assets/css/' );
			define( 'BF_WOO_ELEM_JS_PATH', GFIREM_URL_PATH . 'assets/js/' );
			define( 'BF_WOO_ELEM_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'BF_WOO_ELEM_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR );
			define( 'BF_WOO_ELEM_FIELDS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR );
			define( 'BF_WOO_ELEM_TEMPLATES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR );
		}
		
		function bf_wc_fe_includes() {
			
			include_once( dirname( __FILE__ ) . '/includes/form-builder-elements.php' );
			include_once( dirname( __FILE__ ) . '/includes/class-wc-meta-box-product-data.php' );
			include_once( dirname( __FILE__ ) . '/includes/class-wc-meta-box-product-images.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements-save.php' );
			
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-attribute.php' );
			//include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-variations.php');
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-downloadable.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-general.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-inventory.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-linked.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-shipping.php' );
			include_once( dirname( __FILE__ ) . '/includes/form-elements/bf-wc-product-type.php' );
			
			include_once( dirname( __FILE__ ) . '/includes/wc-admin-assets-frontend/class-wc-admin-assets-frontend.php' );
			
			if ( ! function_exists( 'woocommerce_wp_text_input' ) ) {
				include_once( WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php' );
			}
		}
		
		function bf_wc_admin_enqueue_script( $hook_suffix ) {
			global $post;
			
			if (
				( isset( $post ) && $post->post_type == 'buddyforms'
				  && isset( $_GET['action'] ) && $_GET['action'] == 'edit'
				  || isset( $post ) && $post->post_type == 'buddyforms'
				     && $hook_suffix == 'post-new.php'
				)
				|| $hook_suffix == 'buddyforms_page_create-new-form'
				|| $hook_suffix == 'buddyforms_page_bf_add_ons'
			) {
				wp_enqueue_script( 'buddyforms-woocommerce', plugins_url( '/assets/js/buddyforms-woocommerce.js', __FILE__ ), array( 'jquery' ) );
			}
		}
		
		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
	}
	
	add_action( 'plugins_loaded', array( 'loaderNew', 'get_instance' ), 1 );
}