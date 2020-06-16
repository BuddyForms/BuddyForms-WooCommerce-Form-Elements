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

class bf_woo_elem_fs {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	public function __construct() {
		if ( $this->bwfe_fs_is_parent_active_and_loaded() ) {
			// If parent already included, init add-on.
			$this->bwfe_fs_init();
		} elseif ( $this->bwfe_fs_is_parent_active() ) {
			// Init add-on only after the parent is loaded.
			add_action( 'buddyforms_core_fs_loaded', array( $this, 'bwfe_fs_init' ) );
		} else {
			// Even though the parent is not activated, execute add-on for activation / uninstall hooks.
			$this->bwfe_fs_init();
		}
	}

	public function bwfe_fs_is_parent_active_and_loaded() {
		// Check if the parent's init SDK method exists.
		return function_exists( 'buddyforms_core_fs' );
	}

	public function bwfe_fs_is_parent_active() {
		$active_plugins_basenames = get_option( 'active_plugins' );

		foreach ( $active_plugins_basenames as $plugin_basename ) {
			if ( strpos( $plugin_basename, 'buddyforms/' ) === 0 ||
			     strpos( $plugin_basename, 'buddyforms-premium/' ) === 0
			) {
				return true;
			}
		}

		return false;
	}

	public function bwfe_fs_init() {
		if ( $this->bwfe_fs_is_parent_active_and_loaded() ) {
			// Init Freemius.
			$this->bf_woo_elem_fs();
		}
	}

	/**
	 * @return Freemius
	 */
	public static function getFreemius() {
		global $bf_woo_elem_fs;

		return $bf_woo_elem_fs;
	}

	// Create a helper function for easy SDK access.

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function bf_woo_elem_fs() {
		global $bf_woo_elem_fs;

		try {
			if ( ! isset( $bf_woo_elem_fs ) ) {
				// Include Freemius SDK.
				if ( file_exists( dirname( dirname( __FILE__ ) ) . '/buddyforms/includes/resources/freemius/start.php' ) ) {
					// Try to load SDK from parent plugin folder.
					require_once dirname( dirname( __FILE__ ) ) . '/buddyforms/includes/resources/freemius/start.php';
				} elseif ( file_exists( dirname( dirname( __FILE__ ) ) . '/buddyforms-premium/includes/resources/freemius/start.php' ) ) {
					// Try to load SDK from premium parent plugin folder.
					require_once dirname( dirname( __FILE__ ) ) . '/buddyforms-premium/includes/resources/freemius/start.php';
				}


				$bf_woo_elem_fs = fs_dynamic_init( array(
					'id'             => '415',
					'slug'           => 'buddyforms-woocommerce-form-elements',
					'type'           => 'plugin',
					'public_key'     => 'pk_10dd57bd7180476f0221961d9d2c9',
					'is_premium'     => false,
					'has_paid_plans' => false,
					'parent'         => array(
						'id'         => '391',
						'slug'       => 'buddyforms',
						'public_key' => 'pk_dea3d8c1c831caf06cfea10c7114c',
						'name'       => 'BuddyForms',
					),
					'menu'           => array(
						'slug'       => 'edit.php?post_type=buddyforms-woocommerce-form-elements',
						'first-path' => 'edit.php?post_type=buddyforms&page=buddyforms_welcome_screen',
						'support'    => false,
					),
				) );
			}
		} catch ( Exception $ex ) {
			trigger_error( 'BF-WooEle::' . $ex->getMessage(), E_USER_NOTICE );
		}

		return $bf_woo_elem_fs;
	}
}
