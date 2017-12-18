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

class bf_woo_elem_log {

	function __construct() {
		add_filter( 'aal_init_roles', array( $this, 'aal_init_roles' ) );
	}

	public static function log( $args ) {
		if ( function_exists( "aal_insert_log" ) ) {
			aal_insert_log( $args );
		}
	}

	public function aal_init_roles( $roles ) {
		$roles_existing          = $roles['manage_options'];
		$roles['manage_options'] = array_merge( $roles_existing, array( bf_woo_elem_manager::get_slug() ) );

		return $roles;
	}
}