<?php

function bf_wc_shipping($thepostid, $customfield){ ?>
    <div id="shipping_product_data" class="hide_if_virtual hide_if_grouped hide_if_external">

    <?php

    echo '<div class="options_group">';

    // Hidden Weight ?
    $product_shipping_hidden_weight = isset($customfield['product_shipping_hidden_weight']) ? $customfield['product_shipping_hidden_weight'] : 0;
    if( isset($customfield['product_shipping_hidden']) && in_array('hidden', $customfield['product_shipping_hidden'])){
      echo '<span style="display: none;">';
    }

    // Weight
    if ( wc_product_weight_enabled() ) {
        woocommerce_wp_text_input( array( 'id' => '_weight', 'label' => __( 'Weight', 'woocommerce' ) . ' (' . get_option( 'woocommerce_weight_unit' ) . ')<br>', 'placeholder' => wc_format_localized_decimal( 0 ), 'desc_tip' => 'true', 'description' => __( 'Weight in decimal form', 'woocommerce' ), 'type' => 'text',
        'data_type' => 'decimal', 'value' => $product_shipping_hidden_weight ) );
    }

    // Size fields
    if ( wc_product_dimensions_enabled() ) {
        ?><p class="form-field dimensions_field">
        <label for="product_length"><?php echo __( 'Dimensions', 'woocommerce' ) . ' (' . get_option( 'woocommerce_dimension_unit' ) . ')'; ?></label><br>
							<span class="wrap">

                <?php
                $product_shipping_hidden_dimension_length = esc_attr( wc_format_localized_decimal( get_post_meta( $thepostid, '_length', true ) ) );
                $product_shipping_hidden_dimension_width  = esc_attr( wc_format_localized_decimal( get_post_meta( $thepostid, '_width', true ) ) );
                $product_shipping_hidden_dimension_height = esc_attr( wc_format_localized_decimal( get_post_meta( $thepostid, '_height', true ) ) );

                if( isset($customfield['product_shipping_hidden']) && in_array('hidden', $customfield['product_shipping_hidden'])){
                  $product_shipping_hidden_dimension_length = isset($customfield['product_shipping_hidden_dimension_length']) ? $customfield['product_shipping_hidden_dimension_length'] : 0;
                  $product_shipping_hidden_dimension_width  = isset($customfield['product_shipping_hidden_dimension_width']) ? $customfield['product_shipping_hidden_dimension_width'] : 0;
                  $product_shipping_hidden_dimension_height = isset($customfield['product_shipping_hidden_dimension_height']) ? $customfield['product_shipping_hidden_dimension_height'] : 0;
                }
                ?>

								<input id="product_length" placeholder="<?php _e( 'Length', 'woocommerce' ); ?>" class="input-text wc_input_decimal dimensions_field" size="12" type="text" name="_length" value="<?php echo $product_shipping_hidden_dimension_length ?>" />
								<input placeholder="<?php _e( 'Width', 'woocommerce' ); ?>" class="input-text wc_input_decimal dimensions_field" size="12" type="text" name="_width" value="<?php echo $product_shipping_hidden_dimension_width ?>" />
								<input placeholder="<?php _e( 'Height', 'woocommerce' ); ?>" class="input-text wc_input_decimal last dimensions_field" size="12" type="text" name="_height" value="<?php echo $product_shipping_hidden_dimension_height ?>" />
							</span>
        <img class="help_tip" data-tip="<?php esc_attr_e( 'LxWxH in decimal form', 'woocommerce' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />
        </p><?php
    }

    do_action( 'woocommerce_product_options_dimensions' );

    if( isset($customfield['product_shipping_hidden']) && in_array('hidden', $customfield['product_shipping_hidden']))
        echo '</span>';

    echo '</div>';

    echo '<div class="options_group">';

    if( isset($customfield['product_shipping_hidden']) && in_array('hidden', $customfield['product_shipping_hidden']))
        echo '<span style="display: none;">';
    // Shipping Class
    $classes = get_the_terms( $thepostid, 'product_shipping_class' );
    if ( $classes && ! is_wp_error( $classes ) ) {
        $current_shipping_class = current( $classes )->term_id;
    } else {
        $current_shipping_class = '';
    }

    $args = array(
        'taxonomy'         => 'product_shipping_class',
        'hide_empty'       => 0,
        'show_option_none' => __( 'No shipping class', 'woocommerce' ),
        'name'             => 'product_shipping_class',
        'id'               => 'product_shipping_class',
        'selected'         => $current_shipping_class,
        'class'            => 'select short'
    );

    if( isset($customfield['product_shipping_hidden']) && in_array('hidden', $customfield['product_shipping_hidden'])){
      $current_shipping_class = $customfield['product_shipping_hidden_shipping_class'];
      woocommerce_wp_hidden_input(array( 'id' => 'product_shipping_class', 'value' => $current_shipping_class));
    } else {
      ?>
      <label for="product_shipping_class"><?php _e( 'Shipping class', 'woocommerce' ); ?></label><p class="form-field dimensions_field"><?php wp_dropdown_categories( $args ); ?> <img class="help_tip" data-tip="<?php esc_attr_e( 'Shipping classes are used by certain shipping methods to group similar products.', 'woocommerce' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" /></p>
      <?php
    }

    do_action( 'woocommerce_product_options_shipping' );

    if( isset($customfield['product_shipping_hidden']) && in_array('hidden', $customfield['product_shipping_hidden']))
        echo '</span>';

    echo '</div></div>';
}
?>
