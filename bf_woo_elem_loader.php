<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 20/03/2017
 * Time: 13:59
 */
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
			private function __construct(){
				$this->bf_wc_fe_loader();
				$this->getRequirements();
			}
			function bf_wc_fe_loader() {
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					add_action( 'admin_enqueue_scripts', 'bf_wc_admin_enqueue_script' );
					bf_wc_fe_includes();
				}
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
			 * Check the plugin dependencies
			 */
			function getRequirements(){
				// Only Check for requirements in the admin
				if(!is_admin()){
					return;
				}
				// Require TGM
				require ( dirname(__FILE__) . '/includes/resources/tgm/class-tgm-plugin-activation.php' );
				// Hook required plugins function to the tgmpa_register action
				add_action( 'tgmpa_register', 'bf_wc_tgmpa_register' );

			}
			public function bf_wc_tgmpa_register(){
				// Create the required plugins array
				$plugins['woocommerce'] = array(
					'name'     => 'WooCommerce',
					'slug'     => 'woocommerce',
					'required' => true,
				);

				if ( ! defined( 'BUDDYFORMS_PRO_VERSION' ) ) {
					$plugins['buddyforms'] = array(
						'name'      => 'BuddyForms',
						'slug'      => 'buddyforms',
						'required'  => true,
					);
				}

				$config = array(
					'id'           => 'buddyforms-tgmpa',  // Unique ID for hashing notices for multiple instances of TGMPA.
					'parent_slug'  => 'plugins.php',       // Parent menu slug.
					'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
					'has_notices'  => true,                // Show admin notices or not.
					'dismissable'  => false,               // If false, a user cannot dismiss the nag message.
					'is_automatic' => true,                // Automatically activate plugins after installation or not.
				);

				// Call the tgmpa function to register the required plugins
				tgmpa( $plugins, $config );
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