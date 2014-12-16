<?php

function bf_wc_product_inventory($thepostid, $customfield){
global $post; ?>

    <div id="inventory_product_data">

        <?php

        echo '<div class="options_group">';

        if ( 'yes' == get_option( 'woocommerce_manage_stock' ) ) {

            // manage stock
            woocommerce_wp_checkbox( array( 'id' => '_manage_stock', 'wrapper_class' => 'show_if_simple show_if_variable', 'label' => __( 'Manage stock?', 'woocommerce' ).'<br>', 'description' => __( 'Enable stock management at product level', 'woocommerce' ) ) );

            do_action( 'woocommerce_product_options_stock' );

            echo '<div class="stock_fields show_if_simple show_if_variable">';

            // Stock
            woocommerce_wp_text_input( array(
                'id'                => '_stock',
                'label'             => __( 'Stock Qty', 'woocommerce' ).'<br>',
                'desc_tip'          => true,
                'description'       => __( 'Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'woocommerce' ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' => 'any'
                ),
                'data_type'         => 'stock'
            ) );

            // Backorders?
            woocommerce_wp_select( array( 'id' => '_backorders', 'label' => __( 'Allow Backorders?', 'woocommerce' ), 'options' => array(
                'no'     => __( 'Do not allow', 'woocommerce' ),
                'notify' => __( 'Allow, but notify customer', 'woocommerce' ),
                'yes'    => __( 'Allow', 'woocommerce' )
            ), 'desc_tip' => true, 'description' => __( 'If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0.', 'woocommerce' ) ) );

            do_action( 'woocommerce_product_options_stock_fields' );

            echo '</div>';

        }

        // Stock status
        woocommerce_wp_select( array( 'id' => '_stock_status', 'wrapper_class' => 'hide_if_variable', 'label' => __( 'Stock status', 'woocommerce' ).'<br>', 'options' => array(
            'instock' => __( 'In stock', 'woocommerce' ),
            'outofstock' => __( 'Out of stock', 'woocommerce' )
        ), 'desc_tip' => true, 'description' => __( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'woocommerce' ) ) );

        do_action( 'woocommerce_product_options_stock_status' );

        echo '</div>';

        echo '<div class="options_group show_if_simple show_if_variable">';

        // Individual product
        woocommerce_wp_checkbox( array( 'id' => '_sold_individually', 'wrapper_class' => 'show_if_simple show_if_variable', 'label' => __( 'Sold Individually', 'woocommerce' ).'<br>', 'description' => __( 'Enable this to only allow one of this item to be bought in a single order', 'woocommerce' ) ) );

        do_action( 'woocommerce_product_options_sold_individually' );

        echo '</div>';

        do_action( 'woocommerce_product_options_inventory_product_data' );
        ?>

    </div>

<?php
}