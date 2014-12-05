<?php

function bf_wc_product_advanced(){
    global $thepostid, $post; ?>

    <div id="advanced_product_data" class="panel woocommerce_options_panel">

        <?php

        echo '<div class="options_group hide_if_external">';

        // Purchase note
        woocommerce_wp_textarea_input(  array( 'id' => '_purchase_note', 'label' => __( 'Purchase Note', 'woocommerce' ), 'desc_tip' => 'true', 'description' => __( 'Enter an optional note to send the customer after purchase.', 'woocommerce' ) ) );

        echo '</div>';

        echo '<div class="options_group">';

        // menu_order
        woocommerce_wp_text_input(  array( 'id' => 'menu_order', 'label' => __( 'Menu order', 'woocommerce' ), 'desc_tip' => 'true', 'description' => __( 'Custom ordering position.', 'woocommerce' ), 'value' => intval( $post->menu_order ), 'type' => 'number', 'custom_attributes' => array(
            'step' 	=> '1'
        )  ) );

        echo '</div>';

        echo '<div class="options_group reviews">';

        woocommerce_wp_checkbox( array( 'id' => 'comment_status', 'label' => __( 'Enable reviews', 'woocommerce' ), 'cbvalue' => 'open', 'value' => esc_attr( $post->comment_status ) ) );

        do_action( 'woocommerce_product_options_reviews' );

        echo '</div>';
        ?>

    </div>



<?php }