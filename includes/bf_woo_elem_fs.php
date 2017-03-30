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
//		$this->bf_woo_elem_fs(); //TODO need to be more clear about it
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
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function bf_woo_elem_fs() {
		global $bf_woo_elem_fs;

		if ( ! isset( $bf_woo_elem_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/resources/freemius/start.php';

			$bf_woo_elem_fs = fs_dynamic_init( array(
				'id'                  => '848',
				'slug'                => 'buddyforms-woocommerce-form-elements',
				'type'                => 'plugin',
				'public_key'          => 'pk_47201a0d3289152f576cfa93e7159',
				'is_premium'          => true,
				'has_premium_version' => true,
				'has_addons'          => false,
				'has_paid_plans'      => true,
				'is_org_compliant'    => false,
				'menu'                => array(
					'slug'       => 'buddyforms-woocommerce-form-elements',
					'first-path' => 'admin.php?page=buddyforms-woocommerce-form-elements',
					'support'    => false,
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'          => 'sk_r~jmr--.4&!Q@<Neu>y1>UV)PB.?n',
			) );
		}

		return $bf_woo_elem_fs;
	}
}