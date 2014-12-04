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

add_action('init', 'bf_wc_includes');
function bf_wc_includes(){

    include_once(dirname(__FILE__) . '/includes/form-builder-elements.php');
    include_once(dirname(__FILE__) . '/includes/form-elements.php');
    include_once(dirname(__FILE__) . '/includes/form-elements-save.php');


    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-type.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-linked.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-attribute.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-shipping.php');
    include_once(dirname(__FILE__) . '/includes/form-elements/bf-wc-product-downloadable.php');

    include_once(dirname(__FILE__) . '/includes/wc-admin-assets-frontend/class-wc-admin-assets-frontend.php');

    //echo WC()->plugin_url() . '/includes/admin/wc-meta-box-functions.php';
    include_once(WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php');
}

add_action('wp_enqueue_scripts', 'bf_wc_enqueue_script');
function bf_wc_enqueue_script(){

  // wp_enqueue_script( 'buddyforms-woocommerce', plugins_url( '/assets/js/buddyforms-woocommerce.js' , __FILE__ ), array( 'jquery' ) );

 }

add_action('wp_enqueue_scripts', 'bf_wp_enqueue_style');
function bf_wp_enqueue_style(){

   // wp_enqueue_style( 'buddyforms-woocommerce-css', plugins_url( '/assets/css/buddyforms-woocommerce.css' , __FILE__ ));

}