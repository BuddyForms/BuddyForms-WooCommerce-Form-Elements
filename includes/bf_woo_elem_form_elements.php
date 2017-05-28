<?php

/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

class bf_woo_elem_form_element {
	
	private $current_post_id;
	
	public function __construct() {
		add_filter( 'buddyforms_create_edit_form_display_element', array( $this, 'buddyforms_woocommerce_create_new_form_builder' ), 1, 2 );
		$this->helpTip();
		add_filter( 'woocommerce_product_type_query', array( $this, 'on_woocommerce_product_type_query' ), 10, 2 );
		add_filter( 'woocommerce_process_product_meta', array( $this, 'on_woocommerce_product_type_query' ), 10, 2 );
		add_filter( 'buddyforms_set_post_id_for_draft', array( $this, 'post_id_for_draft' ), 10, 3 );
	}
	
	public function helpTip() {
		if ( ! is_admin() && ! function_exists( 'wc_help_tip' ) ) {
			
			/**
			 * Display a WooCommerce help tip.
			 *
			 * @since  2.5.0
			 *
			 * @param  string $tip      Help tip text
			 * @param  bool $allow_html Allow sanitized HTML if true or escape
			 *
			 * @return string
			 */
			function wc_help_tip( $tip, $allow_html = false ) {
				if ( $allow_html ) {
					$tip = wc_sanitize_tooltip( $tip );
				} else {
					$tip = esc_attr( $tip );
				}
				
				return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
			}
			
		}
	}
	
	public function on_woocommerce_product_type_query( $override, $product_id ) {
		if ( $product_id == $this->current_post_id ) {
			$override = 'simple';
		}
		
		return $override;
	}
	
	public function post_id_for_draft( $post_id, $args, $customfields ) {
		if ( ! empty( $args ) && ! empty( $customfields ) && is_array( $customfields ) && empty( $post_id ) ) {
			$exist = false;
			foreach ( $customfields as $field_id => $field ) {
				if ( $field['slug'] == '_woocommerce' ) {
					$exist = true;
					
					break;
				}
			}
			if ( $exist ) {
				if ( ! empty( $_GET['post'] ) ) {
					$this->current_post_id = $_GET['post'];
				} else {
					if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
						exit;
					}
				}
				if ( empty( $this->current_post_id ) ) {
					$post    = get_default_post_to_edit( 'product', true );
					$post_id = $this->current_post_id = $post->ID;
				} else {
					$post_id = $this->current_post_id;
				}
			}
		}
		
		return $post_id;
	}
	
	/**
	 * @param Form $form
	 * @param array $form_args
	 *
	 * @return mixed
	 */
	public function buddyforms_woocommerce_create_new_form_builder( $form, $form_args ) {
		global $post;
		extract( $form_args );
		
		if ( ! isset( $customfield['type'] ) ) {
			return $form;
		}
		if ( ( $customfield['type'] == 'woocommerce' || $customfield['type'] == 'product-gallery' ) && is_user_logged_in() ) {
			$temp_post = clone $post;
			if ( ! empty( $form_args['post_id'] ) ) {
				$post = get_post( $form_args['post_id'] );
			} else {
				$post                  = get_default_post_to_edit( 'product', true );
				$this->current_post_id = $post->ID;
			}
			
			$id    = 'woocommerce-product-data';
			$title = __( 'Product data', 'woocommerce' );
			if ( $customfield['type'] == 'product-gallery' ) {
				$id    = 'product_images_container';
				$title = __( 'Product gallery', 'woocommerce' );
			}
			
			$this->add_scripts( $post );
			$this->add_styles();
			ob_start();
			echo '<div id="postbox-container" class="woo_elem_container">';
			echo '<div id="' . $id . '" class="postbox" >' . "\n";
			echo "<h2 class='hndle bf_woo'><span class='woo_element_span'>{$title}</span></h2>\n";
			echo '<div class="inside">' . "\n";
			switch ( $customfield['type'] ) {
				case 'woocommerce':
					WC_Meta_Box_Product_Data::output( $post );
					$this->add_general_settings_option( $customfield );
					break;
				case'product-gallery':
					WC_Meta_Box_Product_Images::output( $post );
					break;
			}
			echo "</div>\n";
			echo "</div>\n";
			echo "</div>\n";
			$get_contents = ob_get_contents();
			ob_clean();
			
			$form->addElement( new Element_HTML( $get_contents ) );
			//Load the scripts
			$post = $temp_post;
		}
		
		return $form;
	}
	
	public function add_scripts( $post ) {
		global $wp_query;
		require_once( ABSPATH . 'wp-admin/includes/screen.php' );
		
		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		// Register scripts
		wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION );
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
		wp_register_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting' . $suffix . '.js', array( 'jquery' ), '0.4.2' );
		wp_register_script( 'round', WC()->plugin_url() . '/assets/js/round/round' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'wc-admin-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'accounting', 'round', 'plupload-all', 'stupidtable', 'jquery-tiptip' ), WC_VERSION );
		wp_register_script( 'zeroclipboard', WC()->plugin_url() . '/assets/js/zeroclipboard/jquery.zeroclipboard' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'qrcode', WC()->plugin_url() . '/assets/js/jquery-qrcode/jquery.qrcode' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'stupidtable', WC()->plugin_url() . '/assets/js/stupidtable/stupidtable' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'serializejson', WC()->plugin_url() . '/assets/js/jquery-serializejson/jquery.serializejson' . $suffix . '.js', array( 'jquery' ), '2.6.1' );
		wp_register_script( 'flot', WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'flot-resize', WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.resize' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
		wp_register_script( 'flot-time', WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.time' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
		wp_register_script( 'flot-pie', WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.pie' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
		wp_register_script( 'flot-stack', WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.stack' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
		wp_register_script( 'wc-settings-tax', WC()->plugin_url() . '/assets/js/admin/settings-views-html-settings-tax' . $suffix . '.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-blockui' ), WC_VERSION );
		wp_register_script( 'wc-backbone-modal', WC()->plugin_url() . '/assets/js/admin/backbone-modal' . $suffix . '.js', array( 'underscore', 'backbone', 'wp-util' ), WC_VERSION );
		wp_register_script( 'wc-shipping-zones', WC()->plugin_url() . '/assets/js/admin/wc-shipping-zones' . $suffix . '.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-enhanced-select', 'wc-backbone-modal' ), WC_VERSION );
		wp_register_script( 'wc-shipping-zone-methods', WC()->plugin_url() . '/assets/js/admin/wc-shipping-zone-methods' . $suffix . '.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-backbone-modal' ), WC_VERSION );
		wp_register_script( 'wc-shipping-classes', WC()->plugin_url() . '/assets/js/admin/wc-shipping-classes' . $suffix . '.js', array( 'jquery', 'wp-util', 'underscore', 'backbone' ), WC_VERSION );
		wp_register_script( 'choose', WC()->plugin_url() . '/assets/js/select2/select2' . $suffix . '.js', array( 'jquery' ) );
		wp_enqueue_script( 'choose' );
		wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'choose' ), WC_VERSION );
		wp_localize_script( 'wc-enhanced-select', 'wc_enhanced_select_params', array(
			'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'woocommerce' ),
			'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'woocommerce' ),
			'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce' ),
			'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce' ),
			'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce' ),
			'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce' ),
			'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce' ),
			'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce' ),
			'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce' ),
			'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce' ),
			'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce' ),
			'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce' ),
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'search_products_nonce'     => wp_create_nonce( 'search-products' ),
			'search_customers_nonce'    => wp_create_nonce( 'search-customers' )
		) );
		
		// Accounting
		wp_localize_script( 'accounting', 'accounting_params', array(
			'mon_decimal_point' => wc_get_price_decimal_separator()
		) );
		
		// WooCommerce admin pages
		
		
		wp_enqueue_script( 'woocommerce_admin' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		
		$locale  = localeconv();
		$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
		
		$params = array(
			'i18n_decimal_error'               => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'woocommerce' ), $decimal ),
			'i18n_mon_decimal_error'           => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'woocommerce' ), wc_get_price_decimal_separator() ),
			'i18n_country_iso_error'           => __( 'Please enter in country code with two capital letters.', 'woocommerce' ),
			'i18_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'woocommerce' ),
			'decimal_point'                    => $decimal,
			'mon_decimal_point'                => wc_get_price_decimal_separator()
		);
		
		wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
		
		
		// Products
		if ( in_array( $screen_id, array( 'edit-product' ) ) ) {
			wp_register_script( 'woocommerce_quick-edit', WC()->plugin_url() . '/assets/js/admin/quick-edit' . $suffix . '.js', array( 'jquery', 'woocommerce_admin' ), WC_VERSION );
			wp_enqueue_script( 'woocommerce_quick-edit' );
		}
		// Meta boxes
		wp_enqueue_media();
		wp_register_script( 'wc-admin-product-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-product' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'wc-admin-variation-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-product-variation' . $suffix . '.js', array( 'wc-admin-meta-boxes', 'serializejson', 'media-models' ), WC_VERSION );
		
		wp_enqueue_script( 'wc-admin-product-meta-boxes' );
		wp_enqueue_script( 'wc-admin-variation-meta-boxes' );
		$params = array(
			'post_id'                             => isset( $post->ID ) ? $post->ID : '',
			'plugin_url'                          => WC()->plugin_url(),
			'ajax_url'                            => admin_url( 'admin-ajax.php' ),
			'woocommerce_placeholder_img_src'     => wc_placeholder_img_src(),
			'add_variation_nonce'                 => wp_create_nonce( 'add-variation' ),
			'link_variation_nonce'                => wp_create_nonce( 'link-variations' ),
			'delete_variations_nonce'             => wp_create_nonce( 'delete-variations' ),
			'load_variations_nonce'               => wp_create_nonce( 'load-variations' ),
			'save_variations_nonce'               => wp_create_nonce( 'save-variations' ),
			'bulk_edit_variations_nonce'          => wp_create_nonce( 'bulk-edit-variations' ),
			'i18n_link_all_variations'            => esc_js( sprintf( __( 'Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max %d per run).', 'woocommerce' ), defined( 'WC_MAX_LINKED_VARIATIONS' ) ? WC_MAX_LINKED_VARIATIONS : 50 ) ),
			'i18n_enter_a_value'                  => esc_js( __( 'Enter a value', 'woocommerce' ) ),
			'i18n_enter_menu_order'               => esc_js( __( 'Variation menu order (determines position in the list of variations)', 'woocommerce' ) ),
			'i18n_enter_a_value_fixed_or_percent' => esc_js( __( 'Enter a value (fixed or %)', 'woocommerce' ) ),
			'i18n_delete_all_variations'          => esc_js( __( 'Are you sure you want to delete all variations? This cannot be undone.', 'woocommerce' ) ),
			'i18n_last_warning'                   => esc_js( __( 'Last warning, are you sure?', 'woocommerce' ) ),
			'i18n_choose_image'                   => esc_js( __( 'Choose an image', 'woocommerce' ) ),
			'i18n_set_image'                      => esc_js( __( 'Set variation image', 'woocommerce' ) ),
			'i18n_variation_added'                => esc_js( __( "variation added", 'woocommerce' ) ),
			'i18n_variations_added'               => esc_js( __( "variations added", 'woocommerce' ) ),
			'i18n_no_variations_added'            => esc_js( __( "No variations added", 'woocommerce' ) ),
			'i18n_remove_variation'               => esc_js( __( 'Are you sure you want to remove this variation?', 'woocommerce' ) ),
			'i18n_scheduled_sale_start'           => esc_js( __( 'Sale start date (YYYY-MM-DD format or leave blank)', 'woocommerce' ) ),
			'i18n_scheduled_sale_end'             => esc_js( __( 'Sale end date (YYYY-MM-DD format or leave blank)', 'woocommerce' ) ),
			'i18n_edited_variations'              => esc_js( __( 'Save changes before changing page?', 'woocommerce' ) ),
			'i18n_variation_count_single'         => esc_js( __( '%qty% variation', 'woocommerce' ) ),
			'i18n_variation_count_plural'         => esc_js( __( '%qty% variations', 'woocommerce' ) ),
			'variations_per_page'                 => absint( apply_filters( 'woocommerce_admin_meta_boxes_variations_per_page', 15 ) )
		);
		
		wp_localize_script( 'wc-admin-variation-meta-boxes', 'woocommerce_admin_meta_boxes_variations', $params );
		
		
		wp_register_script( 'wc-admin-coupon-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-coupon' . $suffix . '.js', array( 'wc-admin-meta-boxes' ), WC_VERSION );
		wp_enqueue_script( 'wc-admin-coupon-meta-boxes' );
		
		$post_id  = isset( $post->ID ) ? $post->ID : '';
		$currency = '';
		$order    = wc_get_order( $post_id );
		$currency = $order ? $order->get_order_currency() : '';
		$params   = array(
			'remove_item_notice'            => __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'woocommerce' ),
			'i18n_select_items'             => __( 'Please select some items.', 'woocommerce' ),
			'i18n_do_refund'                => __( 'Are you sure you wish to process this refund? This action cannot be undone.', 'woocommerce' ),
			'i18n_delete_refund'            => __( 'Are you sure you wish to delete this refund? This action cannot be undone.', 'woocommerce' ),
			'i18n_delete_tax'               => __( 'Are you sure you wish to delete this tax column? This action cannot be undone.', 'woocommerce' ),
			'remove_item_meta'              => __( 'Remove this item meta?', 'woocommerce' ),
			'remove_attribute'              => __( 'Remove this attribute?', 'woocommerce' ),
			'name_label'                    => __( 'Name', 'woocommerce' ),
			'remove_label'                  => __( 'Remove', 'woocommerce' ),
			'click_to_toggle'               => __( 'Click to toggle', 'woocommerce' ),
			'values_label'                  => __( 'Value(s)', 'woocommerce' ),
			'text_attribute_tip'            => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ),
			'visible_label'                 => __( 'Visible on the product page', 'woocommerce' ),
			'used_for_variations_label'     => __( 'Used for variations', 'woocommerce' ),
			'new_attribute_prompt'          => __( 'Enter a name for the new attribute term:', 'woocommerce' ),
			'calc_totals'                   => __( 'Calculate totals based on order items, discounts, and shipping?', 'woocommerce' ),
			'calc_line_taxes'               => __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'woocommerce' ),
			'copy_billing'                  => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
			'load_billing'                  => __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'woocommerce' ),
			'load_shipping'                 => __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
			'featured_label'                => __( 'Featured', 'woocommerce' ),
			'prices_include_tax'            => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
			'tax_based_on'                  => esc_attr( get_option( 'woocommerce_tax_based_on' ) ),
			'round_at_subtotal'             => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
			'no_customer_selected'          => __( 'No customer selected', 'woocommerce' ),
			'plugin_url'                    => WC()->plugin_url(),
			'ajax_url'                      => admin_url( 'admin-ajax.php' ),
			'order_item_nonce'              => wp_create_nonce( 'order-item' ),
			'add_attribute_nonce'           => wp_create_nonce( 'add-attribute' ),
			'save_attributes_nonce'         => wp_create_nonce( 'save-attributes' ),
			'calc_totals_nonce'             => wp_create_nonce( 'calc-totals' ),
			'get_customer_details_nonce'    => wp_create_nonce( 'get-customer-details' ),
			'search_products_nonce'         => wp_create_nonce( 'search-products' ),
			'grant_access_nonce'            => wp_create_nonce( 'grant-access' ),
			'revoke_access_nonce'           => wp_create_nonce( 'revoke-access' ),
			'add_order_note_nonce'          => wp_create_nonce( 'add-order-note' ),
			'delete_order_note_nonce'       => wp_create_nonce( 'delete-order-note' ),
			'calendar_image'                => WC()->plugin_url() . '/assets/images/calendar.png',
			'post_id'                       => isset( $post->ID ) ? $post->ID : '',
			'base_country'                  => WC()->countries->get_base_country(),
			'currency_format_num_decimals'  => wc_get_price_decimals(),
			'currency_format_symbol'        => get_woocommerce_currency_symbol( $currency ),
			'currency_format_decimal_sep'   => esc_attr( wc_get_price_decimal_separator() ),
			'currency_format_thousand_sep'  => esc_attr( wc_get_price_thousand_separator() ),
			'currency_format'               => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS
			'rounding_precision'            => wc_get_rounding_precision(),
			'tax_rounding_mode'             => WC_TAX_ROUNDING_MODE,
			'product_types'                 => array_unique( array_merge( array( 'simple', 'grouped', 'variable', 'external' ), array_keys( wc_get_product_types() ) ) ),
			'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce' ),
			'i18n_permission_revoke'        => __( 'Are you sure you want to revoke access to this download?', 'woocommerce' ),
			'i18n_tax_rate_already_exists'  => __( 'You cannot add the same tax rate twice!', 'woocommerce' ),
			'i18n_product_type_alert'       => __( 'Your product has variations! Before changing the product type, it is a good idea to delete the variations to avoid errors in the stock reports.', 'woocommerce' ),
			'i18n_delete_note'              => __( 'Are you sure you wish to delete this note? This action cannot be undone.', 'woocommerce' )
		);
		
		wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $params );
	}
	
	public function add_styles() {
		global $wp_scripts;
		require_once( ABSPATH . 'wp-admin/includes/screen.php' );
		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';
		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';
		
		// Register admin styles
		wp_register_style( 'woocommerce_admin_menu_styles', WC()->plugin_url() . '/assets/css/menu.css', array(), WC_VERSION );
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );
		//wp_register_style( 'woocommerce_admin_dashboard_styles', WC()->plugin_url() . '/assets/css/dashboard.css', array(), WC_VERSION );
		//wp_register_style( 'woocommerce_admin_print_reports_styles', WC()->plugin_url() . '/assets/css/reports-print.css', array(), WC_VERSION, 'print' );
		
		// Sitewide menu CSS
		wp_enqueue_style( 'woocommerce_admin_menu_styles' );
		
		// Admin styles for WC pages only
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_style( 'jquery-ui-style' );
		//wp_enqueue_style( 'wp-color-picker' );
		//wp_enqueue_style( 'woocommerce_admin_dashboard_styles' );
		
		if ( in_array( $screen_id, array( 'woocommerce_page_wc-reports', 'toplevel_page_wc-reports' ) ) ) {
			//wp_enqueue_style( 'woocommerce_admin_print_reports_styles' );
		}
		
		/**
		 * @deprecated 2.3
		 */
		if ( has_action( 'woocommerce_admin_css' ) ) {
			do_action( 'woocommerce_admin_css' );
			_deprecated_function( 'The woocommerce_admin_css action', '2.3', 'admin_enqueue_scripts' );
		}
		
		wp_enqueue_style( 'buddyforms-woocommerce', BF_WOO_ELEM_CSS_PATH . 'buddyforms-woocommerce.css' );
	}
	
	public function add_general_settings_option( $option ) {
		$product_data_tabs_unhandled   = bf_woo_elem_manager::get_unhandled_tabs();
		$product_data_tabs = array_keys( apply_filters( 'woocommerce_product_data_tabs', array_merge( $product_data_tabs_unhandled, array() ) ) );
		if ( ! empty( $product_data_tabs ) ) {
			$product_data_tabs_implemented = apply_filters( 'bf_woo_element_woo_implemented_tab', array() );
			if ( ! empty( $product_data_tabs_implemented ) ) {
				$product_data_tabs = array_diff( $product_data_tabs, $product_data_tabs_implemented );
			}
			if ( ! empty( $product_data_tabs ) ) {
				$option['disable_tabs'] = $product_data_tabs;
			}
		}
		$option['debug'] = SCRIPT_DEBUG;
		wp_enqueue_script( 'general_settings', BF_WOO_ELEM_JS_PATH . 'bf_woo_general_settings.js', array( "jquery" ), null, true );
		wp_localize_script( 'general_settings', 'general_settings_param', $option );
	}
}