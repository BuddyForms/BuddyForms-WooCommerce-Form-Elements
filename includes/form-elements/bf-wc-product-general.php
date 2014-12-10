<?php

function bf_wc_product_general($thepostid, $customfield){ ?>

    <div id="general_product_data" class="panel woocommerce_options_panel"><?php

    echo '<div class="options_group hide_if_grouped">';

    // SKU
    if (wc_product_sku_enabled()) {
        woocommerce_wp_text_input(array('id' => '_sku', 'label' => '<abbr title="' . __('Stock Keeping Unit', 'woocommerce') . '">' . __('SKU', 'woocommerce') . '</abbr>', 'desc_tip' => 'true', 'description' => __('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woocommerce')));
    } else {
        echo '<input type="hidden" name="_sku" value="' . esc_attr(get_post_meta($thepostid, '_sku', true)) . '" />';
    }

    do_action('woocommerce_product_options_sku');

    echo '</div>';

    echo '<div class="options_group show_if_external">';

    // External URL
    woocommerce_wp_text_input(array('id' => '_product_url', 'label' => __('Product URL', 'woocommerce'), 'placeholder' => 'http://', 'description' => __('Enter the external URL to the product.', 'woocommerce')));

    // Button text
    woocommerce_wp_text_input(array('id' => '_button_text', 'label' => __('Button text', 'woocommerce'), 'placeholder' => _x('Buy product', 'placeholder', 'woocommerce'), 'description' => __('This text will be shown on the button linking to the external product.', 'woocommerce')));

    echo '</div>';

    echo '<div class="options_group pricing show_if_simple show_if_external">';

    // Price
    woocommerce_wp_text_input(array('id' => '_regular_price', 'label' => __('Regular Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')', 'data_type' => 'price'));

    // Special Price
    echo '<div id="woocommerce-product-data">';
    woocommerce_wp_text_input(array('id' => '_sale_price', 'data_type' => 'price', 'label' => __('Sale Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')', 'description' => '<a href="#" class="sale_schedule">' . __('Schedule', 'woocommerce') . '</a>'));

    // Special Price date range
    $sale_price_dates_from = ($date = get_post_meta($thepostid, '_sale_price_dates_from', true)) ? date_i18n('Y-m-d', $date) : '';
    $sale_price_dates_to = ($date = get_post_meta($thepostid, '_sale_price_dates_to', true)) ? date_i18n('Y-m-d', $date) : '';

    echo '	<p class="form-field sale_price_dates_fields">
                                    <label for="_sale_price_dates_from">' . __('Sale Price Dates', 'woocommerce') . '</label>
                                    <input type="text" class="short" name="_sale_price_dates_from" id="_sale_price_dates_from" value="' . esc_attr($sale_price_dates_from) . '" placeholder="' . _x('From&hellip;', 'placeholder', 'woocommerce') . ' YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
                                    <input type="text" class="short" name="_sale_price_dates_to" id="_sale_price_dates_to" value="' . esc_attr($sale_price_dates_to) . '" placeholder="' . _x('To&hellip;', 'placeholder', 'woocommerce') . '  YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
                                    <a id="woocommerce-product-data" href="#" class="cancel_sale_schedule">' . __('Cancel', 'woocommerce') . '</a>
                                </p>';
    echo '</div>';
    do_action('woocommerce_product_options_pricing');

    echo '</div>';
    echo '</div>';
}