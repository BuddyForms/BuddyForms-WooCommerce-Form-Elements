<?php

function bf_wc_product_linked($thepostid, $customfield){
    global $post;

    ?>


<div id="linked_product_data" class="panel woocommerce_options_panel">

    <div class="options_group">

        <?php if(!isset($customfield['product_up_sales'])) { ?>
            <p class="form-field"><label for="upsell_ids"><?php _e( 'Up-Sells', 'woocommerce' ); ?></label>
                <select id="upsell_ids" name="upsell_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>">
                    <?php
                    $upsell_ids = get_post_meta( $thepostid, '_upsell_ids', true );
                    $product_ids = ! empty( $upsell_ids ) ? array_map( 'absint',  $upsell_ids ) : null;

                    if ( $product_ids ) {

                        foreach ( $product_ids as $product_id ) {

                            $product = wc_get_product( $product_id );

                            if ( $product ) {
                                echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( $product->get_formatted_name() ) . '</option>';
                            }
                        }
                    }
                    ?>
                </select> <img class="help_tip" data-tip='<?php _e( 'Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woocommerce' ) ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></p>

        <?php } ?>

        <?php if(!isset($customfield['product_cross_sales'])) { ?>
            <p class="form-field"><label for="crosssell_ids"><?php _e( 'Cross-Sells', 'woocommerce' ); ?></label>
                <select id="crosssell_ids" name="crosssell_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>">
                    <?php
                    $crosssell_ids = get_post_meta( $thepostid, '_crosssell_ids', true );
                    $product_ids = ! empty( $crosssell_ids ) ? array_map( 'absint',  $crosssell_ids ) : null;

                    if ( $product_ids ) {

                        foreach ( $product_ids as $product_id ) {

                            $product = wc_get_product( $product_id );

                            if ( $product ) {
                                echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( $product->get_formatted_name() ) . '</option>';
                            }
                        }
                    }
                    ?>
                </select> <img class="help_tip" data-tip='<?php _e( 'Cross-sells are products which you promote in the cart, based on the current product.', 'woocommerce' ) ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></p>


        <?php } ?>
         </div>

    <?php

    echo '<div class="options_group grouping show_if_simple show_if_external">';

    // List Grouped products
    $post_parents = array();
    $post_parents[''] = __( 'Choose a grouped product&hellip;', 'woocommerce' );

    if ( $grouped_term = get_term_by( 'slug', 'grouped', 'product_type' ) ) {

        $posts_in = array_unique( (array) get_objects_in_term( $grouped_term->term_id, 'product_type' ) );

        if ( sizeof( $posts_in ) > 0 ) {

            $args = array(
                'post_type'        => 'product',
                'post_status'      => 'any',
                'numberposts'      => -1,
                'orderby'          => 'title',
                'order'            => 'asc',
                'post_parent'      => 0,
                'suppress_filters' => 0,
                'include'          => $posts_in,
            );

            $grouped_products = get_posts( $args );

            if ( $grouped_products ) {

                foreach ( $grouped_products as $product ) {

                    if ( $product->ID == $thepostid ) {
                        continue;
                    }

                    $post_parents[ $product->ID ] = $product->post_title;
                }
            }
        }

    }

    woocommerce_wp_select( array( 'id' => 'parent_id', 'label' => __( 'Grouping', 'woocommerce' ), 'value' => absint( $post->post_parent ), 'options' => $post_parents, 'desc_tip' => true, 'description' => __( 'Set this option to make this product part of a grouped product.', 'woocommerce' ) ) );

    woocommerce_wp_hidden_input( array( 'id' => 'previous_parent_id', 'value' => absint( $post->post_parent ) ) );

    do_action( 'woocommerce_product_options_grouping' );

    echo '</div>';
    ?>

    <?php do_action( 'woocommerce_product_options_related' ); ?>

</div>

<?php


}
