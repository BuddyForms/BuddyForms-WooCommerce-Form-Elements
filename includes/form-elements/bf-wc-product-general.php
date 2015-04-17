<?php

function bf_wc_product_general($thepostid, $customfield){ ?>

    <div id="general_product_data"><?php

    if(!(isset($customfield['product_sku']) && in_array('hidden', $customfield['product_sku']))){

        echo '<div class="options_group hide_if_grouped">';

        // SKU
        if (wc_product_sku_enabled()) {
            $required       = isset($customfield['product_sku'])?in_array('Required', $customfield['product_sku'])? array('required' => ''):'':'';
            $required_html  = isset($customfield['product_sku'])?in_array('Required', $customfield['product_sku'])? '<span class="required">* </span>':'':'';
            woocommerce_wp_text_input(array( 'custom_attributes' => $required , 'id' => '_sku', 'label' => $required_html.'<abbr title="' . __('Stock Keeping Unit', 'woocommerce') . '">' . __('SKU', 'woocommerce') . '</abbr><br>', 'desc_tip' => 'true', 'description' => __('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woocommerce')));
        } else {
            echo '<input type="hidden" name="_sku" value="' . esc_attr(get_post_meta($thepostid, '_sku', true)) . '" />';
        }

        do_action('woocommerce_product_options_sku');
        echo '</div>';

    }

    echo '<div class="options_group show_if_external">';

    // External URL
    woocommerce_wp_text_input(array('id' => '_product_url', 'label' => __('Product URL', 'woocommerce').'<br>', 'placeholder' => 'http://', 'description' => __('Enter the external URL to the product.', 'woocommerce')));

    // Button text
    woocommerce_wp_text_input(array('id' => '_button_text', 'label' => __('Button text', 'woocommerce').'<br>', 'placeholder' => _x('Buy product', 'placeholder', 'woocommerce'), 'description' => __('This text will be shown on the button linking to the external product.', 'woocommerce')));

    echo '</div>';

    echo '<div class="options_group pricing show_if_simple show_if_external">';

    $required       = isset($customfield['product_regular_price'])?in_array('Required', $customfield['product_regular_price'])? array('required' => ''):'':'';
    $required_html  = isset($customfield['product_regular_price'])?in_array('Required', $customfield['product_regular_price'])? '<span class="required">* </span>':'':'';

    // Price
    woocommerce_wp_text_input(array( 'custom_attributes' => $required , 'id' => '_regular_price', 'label' => $required_html.__('Regular Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')<br>', 'data_type' => 'price'));

    if(!(isset($customfield['product_sales_price']) && in_array('hidden', $customfield['product_sales_price']))){
        $required       = isset($customfield['product_sales_price'])?in_array('Required', $customfield['product_sales_price'])? array('required' => ''):'':'';
        $required_html  = isset($customfield['product_sales_price'])?in_array('Required', $customfield['product_sales_price'])? '<span class="required">* </span>':'':'';
        $description    = isset($customfield['product_sales_price_dates'])?in_array('hidden', $customfield['product_sales_price_dates'])? '':'<a href="#" class="sale_schedule">' . __('Schedule', 'woocommerce') . '</a>':'';

        // Special Price
        woocommerce_wp_text_input(array( 'custom_attributes' => $required , 'id' => '_sale_price', 'data_type' => 'price', 'label' => $required_html.__('Sale Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')<br>', 'description' => $description));
    } else {
        woocommerce_wp_hidden_input(array( 'id' => '_sale_price', 'data_type' => 'price'));
    }

     if(!(isset($customfield['product_sales_price_dates']) && in_array('hidden', $customfield['product_sales_price_dates']))){

         $required       = isset($customfield['product_sales_price_dates'])?in_array('Required', $customfield['product_sales_price_dates'])? 'required':'':'';
         $required_html  = isset($customfield['product_sales_price_dates'])?in_array('Required', $customfield['product_sales_price_dates'])? '<span class="required">* </span>':'':'';
         $required_style  = isset($customfield['product_sales_price_dates'])?in_array('Required', $customfield['product_sales_price_dates'])? 'style="display: block;"':'':'';

         // Special Price date range
        $sale_price_dates_from = ($date = get_post_meta($thepostid, '_sale_price_dates_from', true)) ? date_i18n('Y-m-d', $date) : '';
        $sale_price_dates_to = ($date = get_post_meta($thepostid, '_sale_price_dates_to', true)) ? date_i18n('Y-m-d', $date) : '';

        echo '	<p class="form-field sale_price_dates_fields" '.$required_style.'>
                                    <label for="_sale_price_dates_from">' .$required_html. __('Sale Price Dates', 'woocommerce') . '</label>
                                    <input '.$required.' type="text" class="short" name="_sale_price_dates_from" id="_sale_price_dates_from" value="' . esc_attr($sale_price_dates_from) . '" placeholder="' . _x('From&hellip;', 'placeholder', 'woocommerce') . ' YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
                                    <input '.$required.' type="text" class="short" name="_sale_price_dates_to" id="_sale_price_dates_to" value="' . esc_attr($sale_price_dates_to) . '" placeholder="' . _x('To&hellip;', 'placeholder', 'woocommerce') . '  YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
                                    <a href="#" class="cancel_sale_schedule">' . __('Cancel', 'woocommerce') . '</a>
                                </p>';
    }
    do_action('woocommerce_product_options_pricing');

    echo '</div>';
    echo '</div>';

}