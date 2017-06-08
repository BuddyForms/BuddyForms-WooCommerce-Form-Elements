<?php

/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

class bf_woo_elem_form_builder {
	
	private $load_script = false;
	
	public function __construct() {
		add_filter( 'buddyforms_add_form_element_select_option', array( $this, 'buddyforms_woocommerce_formbuilder_elements_select' ), 1 );
		add_filter( 'buddyforms_form_element_add_field', array( $this, 'buddyforms_woocommerce_create_new_form_builder_form_element' ), 1, 5 );

		add_action( 'admin_footer', array( $this, 'load_js_for_builder' ) );
	}
	
	public function load_js_for_builder( $hook ) {
		if ( $this->load_script ) {
			wp_enqueue_script( 'bf_woo_builder', BF_WOO_ELEM_JS_PATH . 'bf_woo_builder.js', array( "jquery" ), null, true );
			wp_enqueue_style( 'bf_woo_builder', BF_WOO_ELEM_CSS_PATH . 'buddyforms-woocommerce.css' );
		}
	}
	
	public function buddyforms_woocommerce_formbuilder_elements_select( $elements_select_options ) {
		global $post;
		
		if ( $post->post_type != 'buddyforms' ) {
			return;
		}
		
		$elements_select_options['woocommerce']['label']                     = 'WooCommerce';
		$elements_select_options['woocommerce']['class']                     = 'bf_show_if_f_type_post';
		$elements_select_options['woocommerce']['fields']['woocommerce']     = array(
			'label'  => __( 'General Settings', 'buddyforms' ),
			'unique' => 'unique'
		);
		$elements_select_options['woocommerce']['fields']['product-gallery'] = array(
			'label'  => __( 'Product Gallery', 'buddyforms' ),
			'unique' => 'unique'
		);
		
		return $elements_select_options;
	}
	
	public function buddyforms_woocommerce_create_new_form_builder_form_element( $form_fields, $form_slug, $field_type, $field_id ) {
		global $post, $buddyform;
		
		if ( $post->post_type != 'buddyforms' ) {
			return;
		}
		
		$field_id = (string) $field_id;
		
		$this->load_script = true;

		if( !$buddyform ){
			$buddyform         = get_post_meta( $post->ID, '_buddyforms_options', true );
		}

		//    if($buddyform['post_type'] != 'product')
		//        return;
		
		switch ( $field_type ) {
			case 'woocommerce':
				unset( $form_fields );
				$form_fields['hidden']['name'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][name]", 'WooCommerce' );
				$form_fields['hidden']['slug'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", '_woocommerce' );
				
				$form_fields['hidden']['type'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );
				
				$product_type_options = apply_filters( 'product_type_options', array(
					'virtual'      => array(
						'id'            => '_virtual',
						'wrapper_class' => 'show_if_simple',
						'label'         => __( 'Virtual', 'woocommerce' ),
						'description'   => '<b>' . __( 'Virtual products are intangible and aren\'t shipped.', 'woocommerce' ) . '</b>',
						'default'       => 'no'
					),
					'downloadable' => array(
						'id'            => '_downloadable',
						'wrapper_class' => 'show_if_simple',
						'label'         => __( 'Downloadable', 'woocommerce' ),
						'description'   => '<b>' . __( 'Downloadable products give access to a file upon purchase.', 'woocommerce' ) . '</b>',
						'default'       => 'no'
					)
				) );
				
				$product_type_hidden = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_type_hidden'] ) ) {
					$product_type_hidden = $buddyform['form_fields'][ $field_id ]['product_type_hidden'];
				}
				
				$data = $field_id . '_product_type_default ';
				$data .= 'product-type ';
				$data .= $field_id . '_hr1 ';
				foreach ( $product_type_options as $key => $option ) {
					$data .= $field_id . '_' . $key . ' ';
				}
				$element = new Element_Checkbox( "<b>Product Type Hidden</b>", "buddyforms_options[form_fields][" . $field_id . "][product_type_hidden]", array( 'hidden' => __( 'Make the Product Type a Hidden Field', 'buddyforms' ) ), array(
					'id'    => 'product_type_hidden',
					'class' => 'bf_hidden_checkbox',
					'value' => $product_type_hidden
				) );
				$element->setAttribute( 'bf_hidden_checkbox', trim( $data ) );
				$form_fields['general']['product_type_hidden'] = $element;
				
				$product_type_hidden_checked = isset( $buddyform['form_fields'][ $field_id ]['product_type_hidden'] ) ? '' : 'hidden';
				
				$product_type_default = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_type_default'] ) ) {
					$product_type_default = $buddyform['form_fields'][ $field_id ]['product_type_default'];
				}
				
				$product_type = apply_filters( 'default_product_type', 'simple' );
				
				$product_type_selector = apply_filters( 'product_type_selector', array(
					'simple'   => __( 'Simple product', 'woocommerce' ),
					'grouped'  => __( 'Grouped product', 'woocommerce' ),
					'external' => __( 'External/Affiliate product', 'woocommerce' ),
					'variable' => __( 'Variable product', 'woocommerce' )
				), $product_type );
				
				$form_fields['general']['product_type_default'] = new Element_Select( '<b>' . __( 'Default Product Type: ', 'buddyforms' ) . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_type_default]',
					$product_type_selector,
					array(
						'id'       => 'product-type',
						'class'    => ( $product_type_hidden_checked == 'hidden' ) ? 'hidden' : '',
						'value'    => $product_type_default,
						'selected' => isset( $product_type_default ) ? $product_type_default : 'false',
					)
				);
				
				foreach ( $product_type_options as $key => $option ) {
					$product_type_option_value = isset( $buddyform['form_fields'][ $field_id ]['product_type_options'][ esc_attr( $option["id"] ) ] ) ? $buddyform['form_fields'][ $field_id ]['product_type_options'][ esc_attr( $option["id"] ) ] : '';
					
					$element = new Element_Checkbox( $option['description'], "buddyforms_options[form_fields][" . $field_id . "][product_type_options][" . esc_attr( $option['id'] ) . "]", array( $option['id'] => esc_html( $option['label'] ) ), array(
						'id'    => esc_attr( $option['id'] ),
						'value' => $product_type_option_value
					) );
					
					if ( $product_type_hidden_checked == 'hidden' || $product_type_default != 'simple' ) {
						$element->setAttribute( 'class', 'hidden' );
					}
					
					$form_fields['general'][ $key ] = $element;
				}
				
				$element = new Element_HTML( '<hr>' );
				if ( $product_type_hidden_checked == 'hidden' ) {
					$element->setAttribute( 'class', 'hidden' );
				}
				
				$form_fields['general']['hr1'] = $element;
				
				//$form_fields['general']['product_type_default_div_end'] = new Element_HTML('</div>');
				
				$product_sku = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_sku'] ) ) {
					$product_sku = $buddyform['form_fields'][ $field_id ]['product_sku'];
				}
				$form_fields['Inventory']['product_sku'] = new Element_Select( '<b>' . __( 'SKU Field', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_sku]", array(
					'none'     => 'None',
					'hidden'   => __( 'Hide', 'buddyforms' ),
					"required" => __( 'Required', 'buddyforms' )
				), array( 'inline' => 1, 'id' => 'product_sku_' . $field_id, 'value' => $product_sku ) );
				
				$product_regular_price = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_regular_price'] ) ) {
					$product_regular_price = $buddyform['form_fields'][ $field_id ]['product_regular_price'];
				}
				$form_fields['general']['product_regular_price'] = new Element_Checkbox( '<b>' . __( 'Regular Price', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_regular_price]", array( "required" => __( 'Required', 'buddyforms' ) ), array(
					'inline' => 1,
					'id'     => 'product_regular_price_' . $field_id,
					'value'  => $product_regular_price
				) );
				
				$product_sales_price = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_sales_price'] ) ) {
					$product_sales_price = $buddyform['form_fields'][ $field_id ]['product_sales_price'];
				}
				$form_fields['general']['product_sales_price'] = new Element_Select( '<b>' . __( 'Sales Price', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_sales_price]", array(
					'hidden'   => __( 'Hide', 'buddyforms' ),
					'none'     => __( 'Not Required', 'buddyforms' ),
					"required" => __( 'Required', 'buddyforms' )
				), array(
					'inline' => 1,
					'id'     => 'product_sales_price_' . $field_id,
					'value'  => $product_sales_price
				) );
				
				$product_sales_price_dates = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_sales_price_dates'] ) ) {
					$product_sales_price_dates = $buddyform['form_fields'][ $field_id ]['product_sales_price_dates'];
				}
				$form_fields['general']['product_sales_price_dates'] = new Element_Select( '<b>' . __( 'Sales Price Date', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_sales_price_dates]", array(
					'hidden'   => __( 'Hide', 'buddyforms' ),
					'none'     => __( 'Not Required', 'buddyforms' ),
					"required" => __( 'Required', 'buddyforms' )
				), array(
					'inline' => 1,
					'id'     => 'product_sales_price_dates_' . $field_id,
					'value'  => $product_sales_price_dates
				) );
				
				// Inventory
				
				$product_manage_stock = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_manage_stock'] ) ) {
					$product_manage_stock = $buddyform['form_fields'][ $field_id ]['product_manage_stock'];
				}
				
				$product_manage_stock_checked = isset( $buddyform['form_fields'][ $field_id ]['product_manage_stock'] ) ? '' : 'hidden';
				
				$data = $field_id . '_product_type_hidden ';
				$data .= $field_id . '_product_manage_stock_hide ';
				$data .= $field_id . '_product_manage_stock_qty_options ';
				$data .= $field_id . '_product_manage_stock_qty ';
				$data .= $field_id . '_product_allow_backorders_options ';
				$data .= $field_id . '_product_allow_backorders ';
				//$data .= $field_id .'_product_stock_status_options ';
				//$data .= $field_id .'_product_stock_status ';
				//$data .= $field_id .'_product_sold_individually_options ';
				//$data .= $field_id .'_product_sold_individually ';
				
				
				$element = new Element_Checkbox( '<b>' . __( 'Manage Stock', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_manage_stock]", array( 'manage' => __( 'Hide stock management at product level and set default hidden values . ', 'buddyforms' ) ), array(
					'id'    => 'product_manage_stock_' . $field_id,
					'class' => 'bf_hidden_checkbox',
					'value' => $product_manage_stock
				) );
				$element->setAttribute( 'bf_hidden_checkbox', trim( $data ) );
				$form_fields['Inventory']['product_manage_stock'] = $element;
				
				// Stock Qty
				$product_manage_stock_qty_options = isset( $buddyform['form_fields'][ $field_id ]['product_manage_stock_qty_options'] ) ? $buddyform['form_fields'][ $field_id ]['product_manage_stock_qty_options'] : 'false';
				$element                          = new Element_Checkbox( '<b>' . __( 'Stock Qty', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_manage_stock_qty_options]",
					array( 'default' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => 'product_manage_stock_qty_options_' . $field_id,
						'class' => trim( 'bf_hidden_checkbox ' . $product_manage_stock_checked ),
						'value' => $product_manage_stock_qty_options
					)
				);
				$data                             = $field_id . '_product_manage_stock_qty ';
				$element->setAttribute( 'bf_hidden_checkbox', trim( $data ) );
				$form_fields['Inventory']['product_manage_stock_qty_options'] = $element;
				
				// Stock Qty hidden value
				$product_manage_stock_qty_checked = $product_manage_stock_qty_options == 'false' ? 'hidden' : '';
				$product_manage_stock_qty         = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_manage_stock_qty'] ) ) {
					$product_manage_stock_qty = $buddyform['form_fields'][ $field_id ]['product_manage_stock_qty'];
				}
				$form_fields['Inventory']['product_manage_stock_qty'] = new Element_Number( '<b>' . __( 'Enter a number: ', 'buddyforms' ) . ' </b>', "buddyforms_options[form_fields][" . $field_id . "][product_manage_stock_qty]",
					array(
						'id'    => 'product_manage_stock_qty_' . $field_id,
						'class' => $product_manage_stock_checked . ' ' . $product_manage_stock_qty_checked,
						'value' => $product_manage_stock_qty
					)
				);
				
				// Backorders
				$product_allow_backorders_options = isset( $buddyform['form_fields'][ $field_id ]['product_allow_backorders_options'] ) ? $buddyform['form_fields'][ $field_id ]['product_allow_backorders_options'] : 'false';
				$element                          = new Element_Checkbox( '<b>' . __( 'Allow Backorders ? ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_allow_backorders_options]",
					array( 'hidden' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => $field_id . '_product_allow_backorders_options',
						'class' => trim( 'bf_hidden_checkbox ' . $product_manage_stock_checked ),
						'value' => $product_allow_backorders_options
					)
				);
				$data                             = $field_id . '_product_allow_backorders ';
				$element->setAttribute( 'bf_hidden_checkbox', trim( $data ) );
				$form_fields['Inventory']['product_allow_backorders_options'] = $element;
				
				// Backorders value
				$product_allow_backorders_checked                     = $product_allow_backorders_options == 'false' ? 'hidden' : '';
				$product_allow_backorders                             = isset( $buddyform['form_fields'][ $field_id ]['product_allow_backorders'] ) ? $buddyform['form_fields'][ $field_id ]['product_allow_backorders'] : 'false';
				$form_fields['Inventory']['product_allow_backorders'] = new Element_Select( '<b>' . __( 'Select hidden value: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_allow_backorders]",
					array(
						'no'     => __( 'Do not allow', 'buddyforms' ),
						'notify' => __( 'Allow, but notify customer', 'buddyforms' ),
						'yes'    => __( 'Allow', 'buddyforms' )
					),
					array(
						'id'    => $field_id . '_product_allow_backorders',
						'class' => $product_allow_backorders_checked,
						'value' => $product_allow_backorders
					)
				);
				
				// Stock Status
				$product_stock_status_options = isset( $buddyform['form_fields'][ $field_id ]['product_stock_status_options'] ) ? $buddyform['form_fields'][ $field_id ]['product_stock_status_options'] : 'false';
				$element                      = new Element_Checkbox( '<b>' . __( 'Stock Status', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_stock_status_options]",
					array( 'hidden' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => $field_id . '_product_stock_status_options',
						'class' => 'bf_hidden_checkbox',
						'value' => $product_stock_status_options
					)
				);
				$data                         = $field_id . '_product_stock_status ';
				$data                         .= $field_id . '_product_stock_hr1 ';
				$element->setAttribute( 'bf_hidden_checkbox', trim( $data ) );
				$form_fields['Inventory']['product_stock_status_options'] = $element;
				
				// Stock Status Hidden Value
				$product_stock_status_checked                     = $product_stock_status_options == 'false' ? 'hidden' : '';
				$product_stock_status                             = isset( $buddyform['form_fields'][ $field_id ]['product_stock_status'] ) ? $buddyform['form_fields'][ $field_id ]['product_stock_status'] : 'false';
				$form_fields['Inventory']['product_stock_status'] = new Element_Select( '<b>' . __( 'Select hidden value: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_stock_status]",
					array(
						'instock'    => __( 'In stock', 'buddyforms' ),
						'outofstock' => __( 'Out of stock', 'buddyforms' )
					),
					array(
						'id'    => $field_id . '_product_stock_status',
						'class' => $product_stock_status_checked,
						'value' => $product_stock_status
					)
				);
				
				//Add separator to keep the stripe table
				$element = new Element_HTML( '<hr>' );
				if ( $product_type_hidden_checked == 'hidden' ) {
					$element->setAttribute( 'class', 'hidden' );
				}
				$form_fields['Inventory']['product_stock_hr1'] = $element;
				
				// Sold Individually
				$product_sold_individually_options = isset( $buddyform['form_fields'][ $field_id ]['product_sold_individually_options'] ) ? $buddyform['form_fields'][ $field_id ]['product_sold_individually_options'] : 'false';
				$element                           = new Element_Checkbox( '<b>' . __( 'Sold Individually', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_sold_individually_options]",
					array( 'hidden' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => $field_id . '_product_sold_individually_options',
						'class' => 'bf_hidden_checkbox',
						'value' => $product_sold_individually_options
					)
				);
				$data                              = $field_id . '_product_sold_individually';
				$element->setAttribute( 'bf_hidden_checkbox', $data );
				$form_fields['Inventory']['product_sold_individually_options'] = $element;
				
				// Sold Individually Hidden Value
				$product_sold_individually_checked                     = $product_sold_individually_options == 'false' ? 'hidden' : '';
				$product_sold_individually                             = isset( $buddyform['form_fields'][ $field_id ]['product_sold_individually'] ) ? $buddyform['form_fields'][ $field_id ]['product_sold_individually'] : 'false';
				$form_fields['Inventory']['product_sold_individually'] = new Element_Select( '<b>' . __( 'Select hidden value: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_sold_individually]",
					array(
						'yes' => __( 'Yes', 'buddyforms' ),
						'no'  => __( 'No', 'buddyforms' )
					),
					array(
						'id'    => $field_id . '_product_sold_individually',
						'class' => $product_sold_individually_checked,
						'value' => $product_sold_individually
					)
				);
				
				// Shipping
				
				$form_fields['Shipping']['product_shipping_enabled_html'] = new Element_HTML( '<p>' . __( 'If you want to turn off Shipping you need to set the Product Type to Virtual, Grouped or External . In the general Tab . This will automatically disable the shipping fields . ', 'buddyforms' ) . '</p>' );
				
				$product_shipping_hidden = isset( $buddyform['form_fields'][ $field_id ]['product_shipping_hidden'] ) ? $buddyform['form_fields'][ $field_id ]['product_shipping_hidden'] : 'false';
				$element                 = new Element_Checkbox( '<b>' . __( 'Hide Shipping', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_shipping_hidden]",
					array(
						'hidden' => __( 'Hide Shipping fields and set default hidden values . ', 'buddyforms' )
					),
					array(
						'id'    => 'product_shipping_hidden' . $field_id,
						'class' => 'bf_hidden_checkbox',
						'value' => $product_shipping_hidden
					)
				);
				
				$data = $field_id . '_product_shipping_hidden_weight ';
				$data .= $field_id . '_product_shipping_hidden_dimension_length ';
				$data .= $field_id . '_product_shipping_hidden_dimension_width ';
				$data .= $field_id . '_product_shipping_hidden_dimension_height ';
				$data .= $field_id . '_product_shipping_hidden_shipping_class';
				
				$element->setAttribute( 'bf_hidden_checkbox', $data );
				$form_fields['Shipping']['product_shipping_hidden'] = $element;
				
				// Shipping Hidden Value
				$product_shipping_hidden_checked = $product_shipping_hidden == 'false' ? 'hidden' : '';
				
				// Shipping Hidden Weight
				$product_shipping_hidden_weight                            = isset( $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_weight'] ) ? $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_weight'] : 'false';
				$form_fields['Shipping']['product_shipping_hidden_weight'] = new Element_Number( '<b>' . __( 'Weight( kg ): ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_shipping_hidden_weight]",
					array(
						'id'    => $field_id . '_product_shipping_hidden_weight',
						'class' => $product_shipping_hidden_checked,
						'value' => $product_shipping_hidden_weight
					)
				);
				
				// Shipping Hidden Dimension length
				$form_fields['Shipping']['product_shipping_hidden_dimension_length'] = new Element_Number( '<b>' . __( 'Dimension Length: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_shipping_hidden_dimension_length]",
					array(
						'id'    => $field_id . '_product_shipping_hidden_dimension_length',
						'class' => $product_shipping_hidden_checked,
						'value' => isset( $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_dimension_length'] ) ? $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_dimension_length'] : 'false'
					)
				);
				// Shipping Hidden Dimension width
				$form_fields['Shipping']['product_shipping_hidden_dimension_width'] = new Element_Number( '<b>' . __( 'Dimension Width: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_shipping_hidden_dimension_width]",
					array(
						'id'    => $field_id . '_product_shipping_hidden_dimension_width',
						'class' => $product_shipping_hidden_checked,
						'value' => isset( $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_dimension_width'] ) ? $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_dimension_width'] : 'false'
					)
				);
				// Shipping Hidden Dimension height
				$form_fields['Shipping']['product_shipping_hidden_dimension_height'] = new Element_Number( '<b>' . __( 'Dimension Height: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_shipping_hidden_dimension_height]",
					array(
						'id'    => $field_id . '_product_shipping_hidden_dimension_height',
						'class' => $product_shipping_hidden_checked,
						'value' => isset( $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_dimension_height'] ) ? $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_dimension_height'] : 'false'
					)
				);
				
				// Shipping Hidden Shipping Class
				$tax_shipping_class       = array();
				$tax_shipping_class['-1'] = __( 'No shipping class', 'woocommerce' );
				$tax_shipping_class_term  = WC_Shipping::instance()->get_shipping_classes();
				/**
				 * @var integer $key
				 * @var WP_Term $shipping_class_term
				 */
				foreach ( $tax_shipping_class_term as $key => $shipping_class_term ) {
					if ( is_object( $shipping_class_term ) ) {
						$tax_shipping_class[ $shipping_class_term->term_id ] = $shipping_class_term->name;
					}
				}
				if ( ! empty( $tax_shipping_class_term ) ) {
					unset( $tax_shipping_class_term );
				}
				$form_fields['Shipping']['product_shipping_hidden_shipping_class'] = new Element_Select( '<b>' . __( 'Shipping class: ', 'buddyforms' ) . '</b>',
					"buddyforms_options[form_fields][" . $field_id . "][product_shipping_hidden_shipping_class]",
					$tax_shipping_class,
					array(
						'id'    => $field_id . '_product_shipping_hidden_shipping_class',
						'class' => $product_shipping_hidden_checked,
						'value' => isset( $buddyform['form_fields'][ $field_id ]['product_shipping_hidden_shipping_class'] ) ?
							$buddyform['form_fields'][ $field_id ]['product_shipping_hidden_shipping_class'] : '-1',
					)
				);
				
				// Linked-Products
				$product_up_sales = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_up_sales'] ) ) {
					$product_up_sales = $buddyform['form_fields'][ $field_id ]['product_up_sales'];
				}
				$form_fields['Linked-Products']['product_up_sales'] = new Element_Checkbox( '<b>' . __( 'Up - Sales', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_up_sales]", array( 'hidden' => __( 'Hide Up - Sales', 'buddyforms' ) ), array(
					'id'    => 'product_up_sales_' . $field_id,
					'value' => $product_up_sales
				) );
				
				$product_cross_sales = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_cross_sales'] ) ) {
					$product_cross_sales = $buddyform['form_fields'][ $field_id ]['product_cross_sales'];
				}
				$form_fields['Linked-Products']['product_cross_sales'] = new Element_Checkbox( '<b>' . __( 'Cross Sales', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_cross_sales]", array( 'hidden' => __( 'Hide Cross Sales', 'buddyforms' ) ), array(
					'id'    => 'product_cross_sales_' . $field_id,
					'value' => $product_cross_sales
				) );
				
				$product_grouping = 'false';
				if ( isset( $buddyform['form_fields'][ $field_id ]['product_grouping'] ) ) {
					$product_grouping = $buddyform['form_fields'][ $field_id ]['product_grouping'];
				}
				$form_fields['Linked-Products']['product_grouping'] = new Element_Checkbox( '<b>' . __( 'Grouping', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][product_grouping]", array( 'hidden' => __( 'Hide Grouping', 'buddyforms' ) ), array(
					'id'    => 'product_grouping' . $field_id,
					'value' => $product_grouping
				) );
				
				//Attributes
				$form_fields['Attributes']['attributes_hide_tab'] = new Element_Checkbox( '<b>' . __( 'Tab Attributes', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][attributes_hide_tab]", array( 'hidden' => __( 'Hide Attributes Tab', 'buddyforms' ) ), array(
					'id'    => 'attributes_hide_tab_' . $field_id,
					'value' => isset( $buddyform['form_fields'][ $field_id ]['attributes_hide_tab'] ) ? $buddyform['form_fields'][ $field_id ]['attributes_hide_tab'] : 'false'
				) );
				
				//Variations
				$form_fields['Variations']['variations_hide_tab'] = new Element_Checkbox( '<b>' . __( 'Tab Variations', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][variations_hide_tab]", array( 'hidden' => __( 'Hide Variations Tab', 'buddyforms' ) ), array(
					'id'    => 'variations_hide_tab_' . $field_id,
					'value' => isset( $buddyform['form_fields'][ $field_id ]['variations_hide_tab'] ) ? $buddyform['form_fields'][ $field_id ]['variations_hide_tab'] : 'false'
				) );
				
				//Advanced
				//Purchase note
				$hide_element = isset( $buddyform['form_fields'][ $field_id ]['hide_purchase_notes'] ) ? $buddyform['form_fields'][ $field_id ]['hide_purchase_notes'] : 'false';
				$element      = new Element_Checkbox( '<b>' . __( 'Hide Purchase note: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][hide_purchase_notes]",
					array( 'hidden' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => $field_id . '_hide_purchase_notes',
						'class' => 'bf_hidden_checkbox',
						'value' => $hide_element
					)
				);
				$element->setAttribute( 'bf_hidden_checkbox', $field_id . '_purchase_notes' );
				$form_fields['Advanced']['hide_purchase_notes'] = $element;
				$form_fields['Advanced']['purchase_notes']      = new Element_Textarea( '<b>' . __( 'Purchase note: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][purchase_notes]", array(
					'id'    => $field_id . '_purchase_notes',
					'rows'  => '2',
					'cols'  => '20',
					'class' => ( $hide_element == 'false' ) ? 'hidden purchase_notes_class' : 'purchase_notes_class',
					'value' => isset( $buddyform['form_fields'][ $field_id ]['purchase_notes'] ) ? $buddyform['form_fields'][ $field_id ]['purchase_notes'] : ''
				) );
				
				//Menu Order
				$hide_element = isset( $buddyform['form_fields'][ $field_id ]['hide_menu_order'] ) ? $buddyform['form_fields'][ $field_id ]['hide_menu_order'] : 'false';
				$element      = new Element_Checkbox( '<b>' . __( 'Hide Menu order: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][hide_menu_order]",
					array( 'hidden' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => $field_id . '_hide_menu_order',
						'class' => 'bf_hidden_checkbox',
						'value' => $hide_element
					)
				);
				$data         = $field_id . '_menu_order';
				$element->setAttribute( 'bf_hidden_checkbox', $data );
				$form_fields['Advanced']['hide_menu_order'] = $element;
				$form_fields['Advanced']['menu_order']      = new Element_Number( '<b>' . __( 'Menu order: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][menu_order]",
					array(
						'id'    => $field_id . '_menu_order',
						'step'  => '1',
						'class' => ( $hide_element == 'false' ) ? 'hidden' : '',
						'value' => isset( $buddyform['form_fields'][ $field_id ]['menu_order'] ) ? $buddyform['form_fields'][ $field_id ]['menu_order'] : 0
					)
				);
				
				//Enable Review Order
				$hide_element = isset( $buddyform['form_fields'][ $field_id ]['hide_enable_review_orders'] ) ? $buddyform['form_fields'][ $field_id ]['hide_enable_review_orders'] : 'false';
				$element      = new Element_Checkbox( '<b>' . __( 'Hide Enable reviews: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][hide_enable_review_orders]",
					array( 'hidden' => __( 'Hide', 'buddyforms' ) ),
					array(
						'id'    => $field_id . '_hide_enable_review_orders',
						'class' => 'bf_hidden_checkbox',
						'value' => $hide_element
					)
				);
				$data         = $field_id . '_enable_review_orders';
				$element->setAttribute( 'bf_hidden_checkbox', $data );
				$form_fields['Advanced']['hide_enable_review_orders'] = $element;
				$form_fields['Advanced']['enable_review_orders']      = new Element_Select( '<b>' . __( 'Enable reviews Value: ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][enable_review_orders]",
					array(
						'yes' => __( 'Yes', 'buddyforms' ),
						'no'  => __( 'No', 'buddyforms' )
					),
					array(
						'id'    => $field_id . '_enable_review_orders',
						'class' => ( $hide_element == 'false' ) ? 'hidden' : '',
						'value' => isset( $buddyform['form_fields'][ $field_id ]['enable_review_orders'] ) ? $buddyform['form_fields'][ $field_id ]['enable_review_orders'] : 'false'
					)
				);
				
				//Woocommerce front tab handler
				$product_data_tabs_unhandled   = bf_woo_elem_manager::get_unhandled_tabs();
				$product_data_tabs_implemented = apply_filters( 'bf_woo_element_woo_implemented_tab', array() );
				$product_data_tabs             = apply_filters( 'woocommerce_product_data_tabs', array_merge( $product_data_tabs_unhandled, array() ) );
				if ( ! empty( $product_data_tabs ) && is_array( $product_data_tabs ) && count( $product_data_tabs ) > 0 ) {
					foreach ( $product_data_tabs as $tab_key => $tab ) {
						if ( in_array( $tab_key, $product_data_tabs_implemented ) ) {
							continue;
						}
						$tab_value = false;
						if ( isset( $buddyform['form_fields'][ $field_id ][ $tab_key ] ) ) {
							$tab_value = $buddyform['form_fields'][ $field_id ][ $tab_key ];
						}
						$form_fields['Front-Tabs-Handler'][ $tab_key ] = new Element_Checkbox( '<b>' . $tab['label'] . '</b>', "buddyforms_options[form_fields][" . $field_id . "][" . $tab_key . "]", array( 'hidden' => __( 'Remove', 'buddyforms' ) ), array(
							'id'    => $tab_key . $field_id,
							'value' => $tab_value
						) );
					}
				}
				
				break;
			case 'product - gallery':
				unset( $form_fields );
				$form_fields['Gallery']['name']        = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][name]", 'Gallery' );
				$form_fields['Gallery']['slug']        = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", '_gallery' );
				$form_fields['Gallery']['type']        = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );
				$description                           = isset( $buddyform['form_fields'][ $field_id ]['description'] ) ? stripslashes( $buddyform['form_fields'][ $field_id ]['description'] ) : '';
				$form_fields['Gallery']['description'] = new Element_Textbox( '<b>' . __( 'Description', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][description]", array( 'value' => $description ) );
				$required                              = isset( $buddyform['form_fields'][ $field_id ]['required'] ) ? $buddyform['form_fields'][ $field_id ]['required'] : 'false';
				$form_fields['Gallery']['required']    = new Element_Checkbox( '<b>' . __( 'Required', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][required]", array( 'required' => '<b>' . __( 'Make this field a required field', 'buddyforms' ) . '</b>' ), array(
					'value' => $required,
					'id'    => "buddyforms_options[form_fields][" . $field_id . "][required]"
				) );
				break;
		}
		
		return $form_fields;
	}

}

