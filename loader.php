<?php
/*
 Plugin Name: BuddyForms WooCommerce Form Elements
 Plugin URI: http://buddyforms.com
 Description: This Plugin adds a new section to the BuddyForms Form Builder with all WooCommerce fields to create Product creation forms for the frontend
 Version: 1.0
 Author: Sven Lehnert
 Author URI: http://themekraft.com/members/svenl77/
 License: GPLv2 or later
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

add_action('init', 'bf_wc_fe_loader', 10);

function bf_wc_fe_loader(){
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action('init', 'bf_wc_fe_includes', 999);
        add_action('admin_enqueue_scripts', 'bf_wc_admin_enqueue_script');

    }

}


add_action('plugins_loaded', 'bf_wc_fe_requirements');
function bf_wc_fe_requirements(){

    if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BuddyForms WooCommerce Form Elements needs WooCommerce to be installed. <a href="%s">Download it now</a>!\', " buddyforms" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
        return;
    }

    if( ! defined( 'buddyforms' )){
        add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BuddyForms WooCommerce Form Elements needs BuddyForms to be installed. <a target="_blank" href="%s">--> Get it now</a>!\', " wc4bp_xprofile" ) . \'</strong></p></div>\', "http://themekraft.com/store/wordpress-front-end-editor-and-form-builder-buddyforms/" );' ) );
        return;
    }

}

function bf_wc_fe_includes(){

    include_once(dirname(__FILE__) . '/includes/form-builder-elements.php');
    include_once(dirname(__FILE__) . '/includes/form-elements.php');
    include_once(dirname(__FILE__) . '/includes/form-elements-save.php');

    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-attribute.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-downloadable.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-general.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-inventory.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-linked.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-shipping.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-type.php');

    include_once(dirname(__FILE__) . '/includes/wc-admin-assets-frontend/class-wc-admin-assets-frontend.php');

    include_once(WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php');
}

function bf_wc_admin_enqueue_script($hook){
    if($hook == 'toplevel_page_buddyforms_options_page'){
        wp_enqueue_script( 'buddyforms-woocommerce', plugins_url( '/assets/js/buddyforms-woocommerce.js' , __FILE__ ), array( 'jquery' ) );
    }
 }