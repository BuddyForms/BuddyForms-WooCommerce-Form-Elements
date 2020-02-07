<?php

/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

class bf_woo_elem_form_builder
{
    private $load_script = false;

    public function __construct()
    {
        add_filter('buddyforms_add_form_element_select_option', array($this, 'buddyforms_woocommerce_formbuilder_elements_select'), 1);
        add_filter('buddyforms_form_element_add_field', array($this, 'buddyforms_woocommerce_create_new_form_builder_form_element'), 999, 5);
        add_filter('bf_submission_column_default', array($this, 'buddyforms_woo_elem_custom_column_default'), 10, 4);
        add_action('admin_enqueue_scripts', array($this, 'load_js_for_builder'),9999);
    }

    public function buddyforms_woo_elem_custom_column_default($bf_value, $item, $column_name, $field_slug)
    {
        global $buddyforms;
        if ($column_name === 'woocommerce') {
            $url = get_permalink($item->ID);
            $product_title = get_the_title($item->ID);
            return " <a style='vertical-align: top;' target='_blank' href='" . $url . "'>${product_title}</a>";
        }

        if ($column_name === 'product-gallery') {
            $result = '';
            $gallery = $column_val = get_post_meta(intval($item->ID), '_product_image_gallery', true);
            $src = wp_get_attachment_url($gallery);

            if (! empty($gallery) && ! empty($src)) {
                $result = wp_get_attachment_image($gallery, array(50, 50), true) . " <a style='vertical-align: top;' target='_blank' href='" . $src . "'>" . __('Full Image', 'buddyform') . '</a>';
            }
            return $result;
        }

        return $bf_value;
    }

    public function load_js_for_builder($hook)
    {

        if ($this->load_script) {
            $url = BF_WOO_ELEM_JS_PATH;
            wp_enqueue_script('bf_woo_builder',BF_WOO_ELEM_JS_PATH .'bf_woo_builder.js',array(), false,false);


            wp_enqueue_style('bf_woo_builder', BF_WOO_ELEM_CSS_PATH . 'buddyforms-woocommerce.css');
        }
    }

    public function buddyforms_woocommerce_formbuilder_elements_select($elements_select_options)
    {
        global $post;

	    if ( empty( $post ) || $post->post_type !== 'buddyforms' ) {
		    return $elements_select_options;
	    }

        $elements_select_options['woocommerce']['label'] = 'WooCommerce';
        $elements_select_options['woocommerce']['class'] = 'bf_show_if_f_type_post';
        $elements_select_options['woocommerce']['fields']['woocommerce'] = array(
            'label' => __('General Settings', 'buddyforms'),
            'unique' => 'unique',
        );
        $elements_select_options['woocommerce']['fields']['product-gallery'] = array(
            'label' => __('Product Gallery', 'buddyforms'),
            'unique' => 'unique',
        );

        return $elements_select_options;
    }

    public function buddyforms_woocommerce_create_new_form_builder_form_element($form_fields, $form_slug, $field_type, $field_id)
    {
        global $post, $buddyform;

	    if ( empty( $post ) || ( $post->post_type !== 'buddyforms' && $post->post_type !== 'bp_group_type' ) ) {
		    return $form_fields;
	    }

        $field_id = (string) $field_id;

        $this->load_script = true;
       if ($this->load_script) {
            wp_enqueue_script( 'jquery-ui-datepicker1', BF_WOO_ELEM_JS_PATH . 'jquery.datetimepicker.full.js', array('jquery'),null,true);
            wp_enqueue_script('bf_woo_jvalidate', BF_WOO_ELEM_JS_PATH . 'jquery.validate.min.js', array('jquery'),null,true);
            wp_enqueue_script('bf_woo_builder', BF_WOO_ELEM_JS_PATH . 'bf_woo_builder.js', array('jquery','jquery-ui-datepicker1'), null,false);
            do_action('include_bf_woo_booking_scripts');
            wp_enqueue_style('bf_woo_builder', BF_WOO_ELEM_CSS_PATH . 'buddyforms-woocommerce.css');
            wp_enqueue_style('jquery-ui-datepicker2', BF_WOO_ELEM_CSS_PATH . 'jquery.datetimepicker.min.css');
            $param_builder = array('field_id'=>$field_id);
            wp_localize_script( 'bf_woo_builder', 'bf_woo_elem_builder', $param_builder );
        }

        if (! $buddyform) {
            $buddyform = get_post_meta($post->ID, '_buddyforms_options', true);
        }

        //    if($buddyform['post_type'] != 'product')
        //        return;

        switch ($field_type) {
            case 'woocommerce':
                unset($form_fields);
                $form_fields['hidden']['name'] = new Element_Hidden('buddyforms_options[form_fields][' . $field_id . '][name]', 'WooCommerce');
                $form_fields['hidden']['slug'] = new Element_Hidden('buddyforms_options[form_fields][' . $field_id . '][slug]', '_woocommerce');

                $form_fields['hidden']['type'] = new Element_Hidden('buddyforms_options[form_fields][' . $field_id . '][type]', $field_type);

                $product_type_options = apply_filters('product_type_options', array(
                    'virtual' => array(
                        'id' => '_virtual',
                        'wrapper_class' => 'show_if_simple',
                        'label' => __('Virtual', 'woocommerce'),
                        'description' => '<b>' . __('Virtual products are intangible and aren\'t shipped.', 'woocommerce') . '</b>',
                        'default' => 'no',
                    ),
                    'downloadable' => array(
                        'id' => '_downloadable',
                        'wrapper_class' => 'show_if_simple',
                        'label' => __('Downloadable', 'woocommerce'),
                        'description' => '<b>' . __('Downloadable products give access to a file upon purchase.', 'woocommerce') . '</b>',
                        'default' => 'no',
                    ),
                ));
                $is_tax_enabled = wc_tax_enabled();
                if ($is_tax_enabled) {
                    $tax_hidden = false;
                    if (isset($buddyform['form_fields'][$field_id]['product_tax_hidden'])) {
                        $tax_hidden = $buddyform['form_fields'][$field_id]['product_tax_hidden'];
                    }
                    $element_tax = new Element_Checkbox('<b>Product Tax Hidden</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_tax_hidden]', array('hide_product_tax' => __('Make the Product Tax a Hidden Field', 'buddyforms')), array(
                        'id' => 'product_tax_hidden',
                        'class' => 'bf_hidden_checkbox',
                        'value' => $tax_hidden,
                    ));
                    $form_fields['general']['product_tax_hidden'] = $element_tax;
                    $product_tax_hidden_checked = isset($buddyform['form_fields'][$field_id]['product_tax_hidden']) ? '' : 'hidden';
                    $product_tax_status_default = 'false';
                    if (isset($buddyform['form_fields'][$field_id]['product_tax_status_default'])) {
                        $product_tax_status_default = $buddyform['form_fields'][$field_id]['product_tax_status_default'];
                    }


                    $product_tax_status = array(
                        'taxable' => __('Taxable', 'woocommerce'),
                        'shipping' => __('Shipping only', 'woocommerce'),
                        'none' => _x('None', 'Tax status', 'woocommerce'),
                    );
                    $form_fields['general']['product_tax_status_default'] = new Element_Select(
                        '<b>' . __('Tax status: ', 'buddyforms') . '</b>',
                        'buddyforms_options[form_fields][' . $field_id . '][product_tax_status_default]',
                        $product_tax_status,
                        array(
                            'id' => 'product_tax_status_default',
                            'class' => $product_tax_hidden_checked === 'hidden' ? 'hidden' : '',
                            'value' => $product_tax_status_default,
                            'selected' => isset($product_tax_status_default) ? $product_tax_status_default : 'false',
                        )
                    );

                    $product_tax_class = wc_get_product_tax_class_options();
                    $product_tax_class_default = 'false';
                    if (isset($buddyform['form_fields'][$field_id]['product_tax_class_default'])) {
                        $product_tax_class_default = $buddyform['form_fields'][$field_id]['product_tax_class_default'];
                    }
                    $form_fields['general']['product_tax_class_default'] = new Element_Select(
                        '<b>' . __('Tax class: ', 'buddyforms') . '</b>',
                        'buddyforms_options[form_fields][' . $field_id . '][product_tax_class_default]',
                        $product_tax_class,
                        array(
                            'id' => 'product_tax_class_default',
                            'class' => $product_tax_hidden_checked === 'hidden' ? 'hidden' : '',
                            'value' => $product_tax_class_default,
                            'selected' => isset($product_tax_class_default) ? $product_tax_class_default : 'false',
                        )
                    );
                } else {
                    $form_fields['general']['wc_tax_option_disabled'] = new Element_Hidden('buddyforms_options[form_fields][' . $field_id . '][wc_tax_option_disabled]', '_taxOptionDisabled');
                }
                $product_type_hidden = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_type_hidden'])) {
                    $product_type_hidden = $buddyform['form_fields'][$field_id]['product_type_hidden'];
                }

                $data = $field_id . '_product_type_default ';
                $data .= 'product-type ';
                $data .= $field_id . '_hr1 ';
                foreach ($product_type_options as $key => $option) {
                    $data .= $field_id . '_' . $key . ' ';
                }
                $element = new Element_Checkbox('<b>Product Type Hidden</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_type_hidden]', array('hide_product_type' => __('Make the Product Type a Hidden Field', 'buddyforms')), array(
                    'id' => 'product_type_hidden',
                    'class' => 'bf_hidden_checkbox',
                    'value' => $product_type_hidden,
                ));
                $element->setAttribute('bf_hidden_checkbox', trim($data));
                $form_fields['general']['product_type_hidden'] = $element;

                $product_type_hidden_checked = isset($buddyform['form_fields'][$field_id]['product_type_hidden']) ? '' : 'hidden';

                $product_type_default = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_type_default'])) {
                    $product_type_default = $buddyform['form_fields'][$field_id]['product_type_default'];
                }

                $product_type = apply_filters('default_product_type', 'simple');

                $product_type_selector = apply_filters('bf_woo_element_product_type_array', array(
                    'simple' => __('Simple product', 'woocommerce'),
                    'grouped' => __('Grouped product', 'woocommerce'),
                    'external' => __('External/Affiliate product', 'woocommerce'),
                    'variable' => __('Variable product', 'woocommerce'),
                ));

                $form_fields['general']['product_type_default'] = new Element_Select(
                    '<b>' . __('Default Product Type: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_type_default]',
                    $product_type_selector,
                    array(
                        'id' => 'product-type',
                        'class' => $product_type_hidden_checked === 'hidden' ? 'hidden' : '',
                        'value' => $product_type_default,
                        'selected' => isset($product_type_default) ? $product_type_default : 'false',
                    )
                );

                foreach ($product_type_options as $key => $option) {
                    $product_type_option_value = isset($buddyform['form_fields'][$field_id]['product_type_options'][esc_attr($option['id'])]) ? $buddyform['form_fields'][$field_id]['product_type_options'][esc_attr($option['id'])] : '';

                    $element = new Element_Checkbox($option['description'], 'buddyforms_options[form_fields][' . $field_id . '][product_type_options][' . esc_attr($option['id']) . ']', array($option['id'] => esc_html($option['label'])), array(
                        'id' => esc_attr($option['id']),
                        'value' => $product_type_option_value,
                        'class' => $option['wrapper_class'],
                    ));

                    if ($product_type_hidden_checked === '') {
                        $wrapper_classes = '';
                        switch ($product_type_default) {
                            case 'booking':
                                if (strpos($option['wrapper_class'], 'show_if_booking') === false) {
                                    $element->setAttribute('class', 'hidden');
                                }
                                break;
                            case 'simple':
                                if (strpos($option['wrapper_class'], 'show_if_simple') === false) {
                                    $element->setAttribute('class', 'hidden');
                                }
                                break;
                            default:
                                break;
                        }
                    } else {
                        $element->setAttribute('class', 'hidden');
                    }

                    $form_fields['general'][$key] = $element;
                }

                $download_limit = '';
                if (isset($buddyform['form_fields'][$field_id]['download_limit'])) {
                    $download_limit = $buddyform['form_fields'][$field_id]['download_limit'];
                }

                $element_download_limit = new Element_Number(
                    '<b>' . __('Download Limit: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][download_limit]',
                    array(
                        'id' => $field_id . '_download_limit',
                        'class' => '',
                        'placeholder' => 'Ilimited',
                        'value' => $download_limit, 'min' => 0,
                        'shortDesc' => 'Leave blank for an unlimited number of downloads.',
                    )
                );

                $download_expiry = '';
                if (isset($buddyform['form_fields'][$field_id]['download_expiry'])) {
                    $download_expiry = $buddyform['form_fields'][$field_id]['download_expiry'];
                }

                $element_download_expiry = new Element_Number(
                    '<b>' . __('Download Expiry: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][download_expiry]',
                    array(
                        'id' => $field_id . '_download_expiry',
                        'class' => '',
                        'placeholder' => 'Never',
                        'value' => $download_expiry, 'min' => 0,
                        'shortDesc' => 'Enter the number of days that must pass before a download link expires or leave it blank.',
                    )
                );

                $download_name = '';
                if (isset($buddyform['form_fields'][$field_id]['download_name'])) {
                    $download_name = $buddyform['form_fields'][$field_id]['download_name'];
                }

                $element_download_name = new Element_Textbox(
                    '<b>' . __('Download Name: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][download_name]',
                    array(
                        'id' => $field_id . '_download_name',
                        'class' => '',
                        'placeholder' => 'File Name',
                        'value' => $download_name,
                        'shortDesc' => 'This is the name of the download shown to the client.',
                    )
                );

                $download_url = '';
                if (isset($buddyform['form_fields'][$field_id]['download_url'])) {
                    $download_url = $buddyform['form_fields'][$field_id]['download_url'];
                }

                $element_download_url = new Element_Textbox(
                    '<b>' . __('File URL: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][download_url]',
                    array(
                        'id' => $field_id . '_download_url',
                        'class' => '',
                        'placeholder' => 'http://',
                        'value' => $download_url,
                        'shortDesc' => 'This is the URL or absolute path of the file to which clients will have access. The URLs written here should be encoded.',
                    )
                );

                $product_type_option_downloadable = isset($buddyform['form_fields'][$field_id]['product_type_options']['_downloadable'][0]) ? $buddyform['form_fields'][$field_id]['product_type_options']['_downloadable'][0] : '';
                if ($product_type_option_downloadable !== '_downloadable') {
                    $element_download_name->setAttribute('class', 'hidden');
                    $element_download_url->setAttribute('class', 'hidden');
                    $element_download_limit->setAttribute('class', 'hidden');
                    $element_download_expiry->setAttribute('class', 'hidden');
                }

                $form_fields['general']['_download_name'] = $element_download_name;
                $form_fields['general']['_download_url'] = $element_download_url;
                $form_fields['general']['_download_limit'] = $element_download_limit;
                $form_fields['general']['_download_expiry'] = $element_download_expiry;

                $form_fields = apply_filters('bf_woo_booking_default_options', $form_fields, $product_type_default, $field_id);

                $product_regular_price = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_regular_price'])) {
                    $product_regular_price = $buddyform['form_fields'][$field_id]['product_regular_price'];
                }

                $element_regular_price = new Element_Select('<b>' . __('Regular Price', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_regular_price]', array(
                    'hidden' => __('Hide', 'buddyforms'),
                    'none' => __('Not Required', 'buddyforms'),
                    'required' => __('Required', 'buddyforms'),
                ), array(
                    'inline' => 1,
                    'id' => 'product_regular_price_' . $field_id,
                    'value' => $product_regular_price,
                ));
                $regular_price_amount =0;
                if (isset($buddyform['form_fields'][$field_id]['regular_price_amount'])) {
                    $regular_price_amount = $buddyform['form_fields'][$field_id]['regular_price_amount'];
                }
                $element_regular_price_amount= new Element_Textbox(
                    '<b>' . __('Enter Amount: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][regular_price_amount]',
                    array(
                        'id' => $field_id . '_regular_price_amount',
                        'class' => $product_regular_price ==='hidden' ? '':'hidden',
                        'value' => $regular_price_amount,
                        'data-rule-regular-price'=>'true'

                    )
                );






                $product_sales_price = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_sales_price'])) {
                    $product_sales_price = $buddyform['form_fields'][$field_id]['product_sales_price'];
                }

                $element_sales_price = new Element_Select('<b>' . __('Sales Price', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_sales_price]', array(
                    'hidden' => __('Hide', 'buddyforms'),
                    'none' => __('Not Required', 'buddyforms'),
                    'required' => __('Required', 'buddyforms'),
                ), array(
                    'inline' => 1,
                    'id' => 'product_sales_price_' . $field_id,
                    'value' => $product_sales_price,
                ));

                $sales_price_amount =0;
                if (isset($buddyform['form_fields'][$field_id]['sales_price_amount'])) {
                    $sales_price_amount = $buddyform['form_fields'][$field_id]['sales_price_amount'];
                }
                $element_sales_price_amount= new Element_Textbox(
                    '<b>' . __('Enter Amount: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][sales_price_amount]',
                    array(
                        'id' => $field_id . '_sales_price_amount',
                        'class' => $product_sales_price ==='hidden' ? '':'hidden',
                        'value' => $sales_price_amount,
                        'data-rule-sales-price'=>'true'

                    )
                );




                $product_sales_price_dates = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_sales_price_dates'])) {
                    $product_sales_price_dates = $buddyform['form_fields'][$field_id]['product_sales_price_dates'];
                }






                $element_price_date = new Element_Select('<b>' . __('Sales Price Date', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_sales_price_dates]', array(
                    'hidden' => __('Hide', 'buddyforms'),
                    'none' => __('Not Required', 'buddyforms'),
                    'required' => __('Required', 'buddyforms'),
                ), array(
                    'inline' => 1,
                    'id' => 'product_sales_price_dates_' . $field_id,
                    'value' => $product_sales_price_dates,
                ));


                $product_sales_start_date = date('Y-m-d');
                $product_sales_end_date = date('Y-m-d');
                if (isset($buddyform['form_fields'][$field_id]['product_sales_start_date'])) {
                    $product_sales_start_date = $buddyform['form_fields'][$field_id]['product_sales_start_date'];
                }

                if (isset($buddyform['form_fields'][$field_id]['product_sales_end_date'])) {
                    $product_sales_end_date = $buddyform['form_fields'][$field_id]['product_sales_end_date'];
                }

                $element_sales_price_start_date = new Element_Textbox('<b>' . __('Sales Start Date', 'buddyforms') . '</b>','buddyforms_options[form_fields][' . $field_id . '][product_sales_start_date]',array(
                    'id' => 'product_sales_start_date_' . $field_id,
                    'class' => $product_sales_price_dates === 'hidden'? 'bf_datetimepicker':'hidden',
                    'value' => $product_sales_start_date,

                ));

                $element_sales_price_end_date = new Element_Textbox('<b>' . __('Sales End Date', 'buddyforms') . '</b>','buddyforms_options[form_fields][' . $field_id . '][product_sales_end_date]',array(
                    'id' => 'product_sales_end_date_' . $field_id,
                    'class' => $product_sales_price_dates === 'hidden'? 'bf_datetimepicker':'hidden',
                    'value' => $product_sales_end_date,

                ));


                if ($product_type_default === 'booking') {
                    $element_regular_price->setAttribute('class', 'hidden');
                    $element_sales_price->setAttribute('class', 'hidden');
                    $element_price_date->setAttribute('class', 'hidden');
                }
                $form_fields['general']['product_regular_price'] = $element_regular_price;
                $form_fields['general']['regular_price_amount'] = $element_regular_price_amount;
                $form_fields['general']['product_sales_price'] = $element_sales_price;
                $form_fields['general']['sales_price_amount'] = $element_sales_price_amount;
                $form_fields['general']['product_sales_price_dates'] = $element_price_date;



                $form_fields['general']['product_sales_start_date'] = $element_sales_price_start_date;
                $form_fields['general']['product_sales_end_date'] = $element_sales_price_end_date;

                $element = new Element_HTML('<hr>');
                if ($product_type_hidden_checked === 'hidden') {
                    $element->setAttribute('class', 'hidden');
                }




                $form_fields['general']['hr1'] = $element;

                //$form_fields['general']['product_type_default_div_end'] = new Element_HTML('</div>');
                //SKU
                $product_sku = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_sku'])) {
                    $product_sku = $buddyform['form_fields'][$field_id]['product_sku'];
                }
                $form_fields['Inventory']['product_sku'] = new Element_Select('<b>' . __('SKU Field', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_sku]', array(
                    'none' => __('Not Required', 'buddyforms'),
                    'hidden' => __('Hide', 'buddyforms'),
                    'required' => __('Required', 'buddyforms'),
                ), array('inline' => 1, 'id' => 'product_sku_' . $field_id, 'value' => $product_sku));

                $sku_value ="";
                if (isset($buddyform['form_fields'][$field_id]['sku_value'])) {
                    $sku_value = $buddyform['form_fields'][$field_id]['sku_value'];
                }
                $element_sku_value= new Element_Textbox(
                    '<b>' . __('Enter SKU: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][sku_value]',
                    array(
                        'id' => $field_id . '_sku_value',
                        'class' => $product_sku ==='hidden' ? '':'hidden',
                        'value' => $sku_value,



                    )
                );

                $form_fields['Inventory']['sku_value'] = $element_sku_value;


                // Inventory

                $product_manage_stock = 'false';
                $product_manage_stock_checked = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_manage_stock'])) {
                    $product_manage_stock = $buddyform['form_fields'][$field_id]['product_manage_stock'];
                    $product_manage_stock_checked = $product_manage_stock[0] =='manage' ? 'true': 'false';
                }

                $element = new Element_Checkbox('<b>' . __('Manage Stock', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_manage_stock]', array('manage' => __('Hide stock management at product level and set default hidden values . ', 'buddyforms')), array(
                    'id' => 'product_manage_stock_' . $field_id,
                    'value' => $product_manage_stock,
                ));

                $form_fields['Inventory']['product_manage_stock'] = $element;



                //Stock Quantity
                $product_manage_stock_qty = 0;
                if (isset($buddyform['form_fields'][$field_id]['product_manage_stock_qty'])) {
                    $product_manage_stock_qty = $buddyform['form_fields'][$field_id]['product_manage_stock_qty'];
                }
                $form_fields['Inventory']['product_manage_stock_qty'] = new Element_Number(
                    '<b>' . __('Stock Quantity: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_manage_stock_qty]',
                    array(
                        'id' => $field_id.'_product_manage_stock_qty' ,
                        'class' => $product_manage_stock_checked === 'true' ? '' : 'hidden',
                        'value' => $product_manage_stock_qty,
                    )
                );
                //Low Stock Qty hidden value

                $product_low_stock_qty = 0;
                if (isset($buddyform['form_fields'][$field_id]['product_low_stock_qty'])) {
                    $product_low_stock_qty = $buddyform['form_fields'][$field_id]['product_low_stock_qty'];
                }
                $form_fields['Inventory']['product_low_stock_qty'] = new Element_Number(
                    '<b>' . __('Low Stock Quantity: ', 'buddyforms') . ' </b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_low_stock_qty]',
                    array(
                        'id' => $field_id.'_product_low_stock_qty' ,
                        'class' => $product_manage_stock_checked === 'true' ? '' : 'hidden',
                        'value' => $product_low_stock_qty,
                    )
                );

                // Backorders


                // Backorders value

                $product_allow_backorders = isset($buddyform['form_fields'][$field_id]['product_allow_backorders']) ? $buddyform['form_fields'][$field_id]['product_allow_backorders'] : 'no';
                $form_fields['Inventory']['product_allow_backorders'] = new Element_Select(
                    '<b>' . __('Allow Back Orders: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_allow_backorders]',
                    array(
                        'no' => __('Do not allow', 'buddyforms'),
                        'notify' => __('Allow, but notify customer', 'buddyforms'),
                        'yes' => __('Allow', 'buddyforms'),
                    ),
                    array(
                        'id' => $field_id . '_product_allow_backorders',
                        'class' => $product_manage_stock_checked === 'true' ? '' : 'hidden',
                        'value' => $product_allow_backorders,
                    )
                );

                // Stock Status
                $product_stock_status_options = isset($buddyform['form_fields'][$field_id]['product_stock_status_options']) ? $buddyform['form_fields'][$field_id]['product_stock_status_options'] : 'none';
                if($product_manage_stock_checked=== 'true'){

                    $product_stock_status_options = 'none';
                }


                $form_fields['Inventory']['product_stock_status_options'] = new Element_Select('<b>' . __('Stock Status Option', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_stock_status_options]', array(
                    'none' => __('Not Required', 'buddyforms'),
                    'hidden' => __('Hide', 'buddyforms'),
                    'required' => __('Required', 'buddyforms'),
                ), array('inline' => 1, 'id' => $field_id.'_product_stock_status_options'  , 'value' => $product_stock_status_options,'class' => $product_manage_stock_checked === 'true' ? 'hidden' : ''));



                //Stock Status Value

                $stock_status_value ="";
                if (isset($buddyform['form_fields'][$field_id]['product_stock_status'])) {
                    $stock_status_value = $buddyform['form_fields'][$field_id]['product_stock_status'];
                }
                $form_fields['Inventory']['product_stock_status'] = new Element_Select('<b>' . __('Select Hidden Value', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_stock_status]', array(
                    'instock' => __('In Stock', 'buddyforms'),
                    'outofstock' => __('Out of Stock', 'buddyforms'),
                    'onbackorder' => __('On Back Order', 'buddyforms'),
                ), array('inline' => 1, 'class' => $product_stock_status_options ==='hidden' ? '':'hidden', 'id' => $field_id . '_product_stock_status' , 'value' => $stock_status_value));





                // Sold Individually
                $product_sold_individually_options = isset($buddyform['form_fields'][$field_id]['product_sold_individually_options']) ? $buddyform['form_fields'][$field_id]['product_sold_individually_options'] : 'false';


                $form_fields['Inventory']['product_sold_individually_options'] = new Element_Select('<b>' . __('Sold Individually', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_sold_individually_options]', array(
                    'none' => __('Not Required', 'buddyforms'),
                    'hidden' => __('Hide', 'buddyforms'),
                    'required' => __('Required', 'buddyforms'),
                ), array('inline' => 1, 'id' => 'product_sold_individually_options' . $field_id, 'value' => $product_sold_individually_options));





                // Sold Individually Hidden Value
                $product_sold_individually_checked = $product_sold_individually_options === 'false' ? 'hidden' : '';
                $product_sold_individually = isset($buddyform['form_fields'][$field_id]['product_sold_individually']) ? $buddyform['form_fields'][$field_id]['product_sold_individually'] : 'false';
                $form_fields['Inventory']['product_sold_individually'] = new Element_Select(
                    '<b>' . __('Select hidden value: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_sold_individually]',
                    array(
                        'yes' => __('Yes', 'buddyforms'),
                        'no' => __('No', 'buddyforms'),
                    ),
                    array(
                        'id' => $field_id . '_product_sold_individually',
                        'class' => $product_sold_individually_options ==='hidden' ? '':'hidden',
                        'value' => $product_sold_individually,
                    )
                );

                // Shipping

                $form_fields['Shipping']['product_shipping_enabled_html'] = new Element_HTML('<p>' . __('If you want to turn off Shipping you need to set the Product Type to Virtual, Grouped or External . In the general Tab . This will automatically disable the shipping fields . ', 'buddyforms') . '</p>');

                $product_shipping_hidden = isset($buddyform['form_fields'][$field_id]['product_shipping_hidden']) ? $buddyform['form_fields'][$field_id]['product_shipping_hidden'] : 'false';
                $element = new Element_Checkbox(
                    '<b>' . __('Hide Shipping', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_shipping_hidden]',
                    array(
                        'hide_shipping' => __('Hide Shipping fields and set default hidden values . ', 'buddyforms'),
                    ),
                    array(
                        'id' => 'product_shipping_hidden' . $field_id,
                        'class' => 'bf_hidden_checkbox',
                        'value' => $product_shipping_hidden,
                    )
                );

                $data = $field_id . '_product_shipping_hidden_weight ';
                $data .= $field_id . '_product_shipping_hidden_dimension_length ';
                $data .= $field_id . '_product_shipping_hidden_dimension_width ';
                $data .= $field_id . '_product_shipping_hidden_dimension_height ';
                $data .= $field_id . '_product_shipping_hidden_shipping_class';

                $element->setAttribute('bf_hidden_checkbox', $data);
                $form_fields['Shipping']['product_shipping_hidden'] = $element;

                // Shipping Hidden Value
                $product_shipping_hidden_checked = $product_shipping_hidden === 'false' ? 'hidden' : '';

                // Shipping Hidden Weight
                $product_shipping_hidden_weight = isset($buddyform['form_fields'][$field_id]['product_shipping_hidden_weight']) ? $buddyform['form_fields'][$field_id]['product_shipping_hidden_weight'] : 'false';
                $form_fields['Shipping']['product_shipping_hidden_weight'] = new Element_Number(
                    '<b>' . __('Weight( kg ): ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_shipping_hidden_weight]',
                    array(
                        'id' => $field_id . '_product_shipping_hidden_weight',
                        'class' => $product_shipping_hidden_checked,
                        'value' => $product_shipping_hidden_weight,
                    )
                );

                // Shipping Hidden Dimension length
                $form_fields['Shipping']['product_shipping_hidden_dimension_length'] = new Element_Number(
                    '<b>' . __('Dimension Length: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_shipping_hidden_dimension_length]',
                    array(
                        'id' => $field_id . '_product_shipping_hidden_dimension_length',
                        'class' => $product_shipping_hidden_checked,
                        'value' => isset($buddyform['form_fields'][$field_id]['product_shipping_hidden_dimension_length']) ? $buddyform['form_fields'][$field_id]['product_shipping_hidden_dimension_length'] : 'false',
                    )
                );
                // Shipping Hidden Dimension width
                $form_fields['Shipping']['product_shipping_hidden_dimension_width'] = new Element_Number(
                    '<b>' . __('Dimension Width: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_shipping_hidden_dimension_width]',
                    array(
                        'id' => $field_id . '_product_shipping_hidden_dimension_width',
                        'class' => $product_shipping_hidden_checked,
                        'value' => isset($buddyform['form_fields'][$field_id]['product_shipping_hidden_dimension_width']) ? $buddyform['form_fields'][$field_id]['product_shipping_hidden_dimension_width'] : 'false',
                    )
                );
                // Shipping Hidden Dimension height
                $form_fields['Shipping']['product_shipping_hidden_dimension_height'] = new Element_Number(
                    '<b>' . __('Dimension Height: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_shipping_hidden_dimension_height]',
                    array(
                        'id' => $field_id . '_product_shipping_hidden_dimension_height',
                        'class' => $product_shipping_hidden_checked,
                        'value' => isset($buddyform['form_fields'][$field_id]['product_shipping_hidden_dimension_height']) ? $buddyform['form_fields'][$field_id]['product_shipping_hidden_dimension_height'] : 'false',
                    )
                );

                // Shipping Hidden Shipping Class
                $tax_shipping_class = array();
                $tax_shipping_class['-1'] = __('No shipping class', 'woocommerce');
                $tax_shipping_class_term = WC_Shipping::instance()->get_shipping_classes();
                /**
                 * @var integer
                 * @var WP_Term $shipping_class_term
                 */
                foreach ($tax_shipping_class_term as $shipping_class_term) {
                    if (is_object($shipping_class_term)) {
                        $tax_shipping_class[$shipping_class_term->term_id] = $shipping_class_term->name;
                    }
                }
                if (! empty($tax_shipping_class_term)) {
                    unset($tax_shipping_class_term);
                }
                $form_fields['Shipping']['product_shipping_hidden_shipping_class'] = new Element_Select(
                    '<b>' . __('Shipping class: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][product_shipping_hidden_shipping_class]',
                    $tax_shipping_class,
                    array(
                        'id' => $field_id . '_product_shipping_hidden_shipping_class',
                        'class' => $product_shipping_hidden_checked,
                        'value' => isset($buddyform['form_fields'][$field_id]['product_shipping_hidden_shipping_class']) ?
                            $buddyform['form_fields'][$field_id]['product_shipping_hidden_shipping_class'] : '-1',
                    )
                );

                // Linked-Products
                $product_up_sales = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_up_sales'])) {
                    $product_up_sales = $buddyform['form_fields'][$field_id]['product_up_sales'];
                }
                $form_fields['Linked-Products']['product_up_sales'] = new Element_Checkbox('<b>' . __('Up - Sales', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_up_sales]', array('hide_up_sales' => __('Hide Up - Sales', 'buddyforms')), array(
                    'id' => 'product_up_sales_' . $field_id,
                    'value' => $product_up_sales,
                ));

                $product_cross_sales = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_cross_sales'])) {
                    $product_cross_sales = $buddyform['form_fields'][$field_id]['product_cross_sales'];
                }
                $form_fields['Linked-Products']['product_cross_sales'] = new Element_Checkbox('<b>' . __('Cross Sales', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_cross_sales]', array('hide_cross_sales' => __('Hide Cross Sales', 'buddyforms')), array(
                    'id' => 'product_cross_sales_' . $field_id,
                    'value' => $product_cross_sales,
                ));

                $product_grouping = 'false';
                if (isset($buddyform['form_fields'][$field_id]['product_grouping'])) {
                    $product_grouping = $buddyform['form_fields'][$field_id]['product_grouping'];
                }
                $form_fields['Linked-Products']['product_grouping'] = new Element_Checkbox('<b>' . __('Grouping', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][product_grouping]', array('hide_grouping' => __('Hide Grouping', 'buddyforms')), array(
                    'id' => 'product_grouping' . $field_id,
                    'value' => $product_grouping,
                ));

                //Attributes
                $form_fields['Attributes']['attributes_hide_tab'] = new Element_Checkbox('<b>' . __('Tab Attributes', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][attributes_hide_tab]', array('hide_attributes' => __('Hide Attributes Tab', 'buddyforms')), array(
                    'id' => 'attributes_hide_tab_' . $field_id,
                    'value' => isset($buddyform['form_fields'][$field_id]['attributes_hide_tab']) ? $buddyform['form_fields'][$field_id]['attributes_hide_tab'] : 'false',
                ));

                //Variations
                $form_fields['Variations']['variations_hide_tab'] = new Element_Checkbox('<b>' . __('Tab Variations', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][variations_hide_tab]', array('hide_variations' => __('Hide Variations Tab', 'buddyforms')), array(
                    'id' => 'variations_hide_tab_' . $field_id,
                    'value' => isset($buddyform['form_fields'][$field_id]['variations_hide_tab']) ? $buddyform['form_fields'][$field_id]['variations_hide_tab'] : 'false',
                ));

                //Advanced
                //Purchase note
                $hide_element = isset($buddyform['form_fields'][$field_id]['hide_purchase_notes']) ? $buddyform['form_fields'][$field_id]['hide_purchase_notes'] : 'false';
                $element = new Element_Checkbox(
                    '<b>' . __('Hide Purchase note: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][hide_purchase_notes]',
                    array('hide_advanced' => __('Hide', 'buddyforms')),
                    array(
                        'id' => $field_id . '_hide_purchase_notes',
                        'class' => 'bf_hidden_checkbox',
                        'value' => $hide_element,
                    )
                );
                $element->setAttribute('bf_hidden_checkbox', $field_id . '_purchase_notes');
                $form_fields['Advanced']['hide_purchase_notes'] = $element;
                $form_fields['Advanced']['purchase_notes'] = new Element_Textarea('<b>' . __('Purchase note: ', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][purchase_notes]', array(
                    'id' => $field_id . '_purchase_notes',
                    'rows' => '2',
                    'cols' => '20',
                    'class' => $hide_element === 'false' ? 'hidden purchase_notes_class' : 'purchase_notes_class',
                    'value' => isset($buddyform['form_fields'][$field_id]['purchase_notes']) ? $buddyform['form_fields'][$field_id]['purchase_notes'] : '',
                ));

                //Menu Order
                $hide_element = isset($buddyform['form_fields'][$field_id]['hide_menu_order']) ? $buddyform['form_fields'][$field_id]['hide_menu_order'] : 'false';
                $element = new Element_Checkbox(
                    '<b>' . __('Hide Menu order: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][hide_menu_order]',
                    array('hide_menu_order' => __('Hide', 'buddyforms')),
                    array(
                        'id' => $field_id . '_hide_menu_order',
                        'class' => 'bf_hidden_checkbox',
                        'value' => $hide_element,
                    )
                );
                $data = $field_id . '_menu_order';
                $element->setAttribute('bf_hidden_checkbox', $data);
                $form_fields['Advanced']['hide_menu_order'] = $element;
                $form_fields['Advanced']['menu_order'] = new Element_Number(
                    '<b>' . __('Menu order: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][menu_order]',
                    array(
                        'id' => $field_id . '_menu_order',
                        'step' => '1',
                        'class' => $hide_element === 'false' ? 'hidden' : '',
                        'value' => isset($buddyform['form_fields'][$field_id]['menu_order']) ? $buddyform['form_fields'][$field_id]['menu_order'] : 0,
                    )
                );

                //Enable Review Order
                $hide_element = isset($buddyform['form_fields'][$field_id]['hide_enable_review_orders']) ? $buddyform['form_fields'][$field_id]['hide_enable_review_orders'] : 'false';
                $element = new Element_Checkbox(
                    '<b>' . __('Hide Enable reviews: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][hide_enable_review_orders]',
                    array('hide_review_order' => __('Hide', 'buddyforms')),
                    array(
                        'id' => $field_id . '_hide_enable_review_orders',
                        'class' => 'bf_hidden_checkbox',
                        'value' => $hide_element,
                    )
                );
                $data = $field_id . '_enable_review_orders';
                $element->setAttribute('bf_hidden_checkbox', $data);
                $form_fields['Advanced']['hide_enable_review_orders'] = $element;
                $form_fields['Advanced']['enable_review_orders'] = new Element_Select(
                    '<b>' . __('Enable reviews Value: ', 'buddyforms') . '</b>',
                    'buddyforms_options[form_fields][' . $field_id . '][enable_review_orders]',
                    array(
                        'yes' => __('Yes', 'buddyforms'),
                        'no' => __('No', 'buddyforms'),
                    ),
                    array(
                        'id' => $field_id . '_enable_review_orders',
                        'class' => $hide_element === 'false' ? 'hidden' : '',
                        'value' => isset($buddyform['form_fields'][$field_id]['enable_review_orders']) ? $buddyform['form_fields'][$field_id]['enable_review_orders'] : 'false',
                    )
                );

                //Woocommerce front tab handler
                $product_data_tabs_unhandled = bf_woo_elem_manager::get_unhandled_tabs();
                $product_data_tabs_implemented = apply_filters('bf_woo_element_woo_implemented_tab', array());
                $product_data_tabs = apply_filters('woocommerce_product_data_tabs', array_merge($product_data_tabs_unhandled, array()));
                $form_fields['Front-Tabs-Handler']['product_data_tabs_implemented'] = new Element_HTML('<h2>' . __('The Front Tabs Handler allow hide or show the Woocommerce Tabs that are not integrated with the BuddyForms-Wocommerce-Form-Element plugin ', 'buddyforms') . '</h2>');
                if (! empty($product_data_tabs) && is_array($product_data_tabs) && count($product_data_tabs) > 0) {
                    foreach ($product_data_tabs as $tab_key => $tab) {
                        if (in_array($tab_key, $product_data_tabs_implemented, true)) {
                            continue;
                        }
                        $tab_value = false;
                        if (isset($buddyform['form_fields'][$field_id][$tab_key])) {
                            $tab_value = $buddyform['form_fields'][$field_id][$tab_key];
                        }
                        $form_fields['Front-Tabs-Handler'][$tab_key] = new Element_Checkbox('<b>' . $tab['label'] . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][' . $tab_key . ']', array('hide_remove' => __('Remove', 'buddyforms')), array(
                            'id' => $tab_key . $field_id,
                            'value' => $tab_value,
                        ));
                    }
                }

                break;
            case 'product-gallery':
                unset($form_fields);
                $name = isset($buddyform['form_fields'][$field_id]['name']) ? stripcslashes($buddyform['form_fields'][$field_id]['name']) : __('Product gallery', 'woocommerce');
                $form_fields['general']['name'] = new Element_Textbox('<b>' . __('Label', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][name]', array(
                    'value' => $name,
                    'required' => 1,
                ));

                $description = isset($buddyform['form_fields'][$field_id]['description']) ? stripcslashes($buddyform['form_fields'][$field_id]['description']) : '';
                $form_fields['general']['description'] = new Element_Textbox('<b>' . __('Description:', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][description]', array('value' => $description));

                $button_text = isset($buddyform['form_fields'][$field_id]['button_text']) ? stripcslashes($buddyform['form_fields'][$field_id]['button_text']) : __('Add product gallery images', 'woocommerce');
                $form_fields['general']['button_text'] = new Element_Textbox('<b>' . __('Button Text:', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][button_text]', array('value' => $button_text));

                $form_fields['hidden']['slug'] = new Element_Hidden('buddyforms_options[form_fields][' . $field_id . '][slug]', '_gallery');
                $form_fields['hidden']['type'] = new Element_Hidden('buddyforms_options[form_fields][' . $field_id . '][type]', $field_type);

                $required = isset($buddyform['form_fields'][$field_id]['required']) ? $buddyform['form_fields'][$field_id]['required'] : 'false';
                $form_fields['validation']['required'] = new Element_Checkbox('<b>' . __('Required', 'buddyforms') . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][required]', array('required' => '<b>' . __('Make this field a required field', 'buddyforms') . '</b>'), array(
                    'value' => $required,
                    'id' => 'buddyforms_options[form_fields][' . $field_id . '][required]',
                ));

                $field_slug = isset($buddyform['form_fields'][$field_id]['slug']) ? $buddyform['form_fields'][$field_id]['slug'] : '';
                $field_slug = empty($field_slug) === false ? buddyforms_sanitize_slug($field_slug) : 'product-gallery';
                $form_fields['advanced']['slug'] = new Element_Textbox('<b>' . __('Slug', 'buddyforms') . '</b> <small>(optional)</small>', 'buddyforms_options[form_fields][' . $field_id . '][slug]', array(
                    'shortDesc' => __('Underscore before the slug like _name will create a hidden post meta field', 'buddyforms'),
                    'value' => $field_slug,
                    'required' => 1,
                    'class' => 'slug' . $field_id,
                ));
                break;
        }

        return $form_fields;
    }
}
