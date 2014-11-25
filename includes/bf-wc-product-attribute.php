<?php

function bf_wc_attrebutes_custom($thepostid){ ?>


        <div class="product_attributes">

            <?php

            // Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set
            $attributes = maybe_unserialize( get_post_meta( $thepostid, '_product_attributes', true ) );

            $i = -1;

            // Custom Attributes
            if ( ! empty( $attributes ) ) {

                foreach ( $attributes as $attribute ) {

                    if ( $attribute['is_taxonomy'] ) {
                        continue;
                    }

                    $i++;

                    $position = empty( $attribute['position'] ) ? 0 : absint( $attribute['position'] );
                    ?>
                    <div class="woocommerce_attribute wc-metabox closed" rel="<?php echo $position; ?>">
                        <h3>
                            <button type="button" class="remove_row button"><?php _e( 'Remove', 'woocommerce' ); ?></button>
                            <div class="handlediv" title="<?php _e( 'Click to toggle', 'woocommerce' ); ?>"></div>
                            <strong class="attribute_name"><?php echo apply_filters( 'woocommerce_attribute_label', esc_html( $attribute['name'] ), esc_html( $attribute['name'] ) ); ?></strong>
                        </h3>
                        <table cellpadding="0" cellspacing="0" class="woocommerce_attribute_data wc-metabox-content">
                            <tbody>
                            <tr>
                                <td class="attribute_name">
                                    <label><?php _e( 'Name', 'woocommerce' ); ?>:</label>
                                    <input type="text" class="attribute_name" name="attribute_names[<?php echo $i; ?>]" value="<?php echo esc_attr( $attribute['name'] ); ?>" />
                                    <input type="hidden" name="attribute_position[<?php echo $i; ?>]" class="attribute_position" value="<?php echo esc_attr( $position ); ?>" />
                                    <input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="0" />
                                </td>
                                <td rowspan="3">
                                    <label><?php _e( 'Value(s)', 'woocommerce' ); ?>:</label>
                                    <textarea name="attribute_values[<?php echo $i; ?>]" cols="5" rows="5" placeholder="<?php _e( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ); ?>"><?php echo esc_textarea( $attribute['value'] ); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label><input type="checkbox" class="checkbox" <?php checked( $attribute['is_visible'], 1 ); ?> name="attribute_visibility[<?php echo $i; ?>]" value="1" /> <?php _e( 'Visible on the product page', 'woocommerce' ); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="enable_variation show_if_variable">
                                        <label><input type="checkbox" class="checkbox" <?php checked( $attribute['is_variation'], 1 ); ?> name="attribute_variation[<?php echo $i; ?>]" value="1" /> <?php _e( 'Used for variations', 'woocommerce' ); ?></label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                <?php
                }
            }
            ?>
            <table cellpadding="0" cellspacing="0" class="woocommerce_attribute_data wc-metabox-content">
                <tbody>
                <tr>
                    <td class="attribute_name">
                        <label><?php _e( 'Name', 'woocommerce' ); ?>:</label>
                        <input type="text" class="attribute_name" name="attribute_names[]" value="" />
                        <input type="hidden" name="attribute_position[]" class="attribute_position" value="" />
                        <input type="hidden" name="attribute_is_taxonomy[]" value="0" />
                    </td>
                    <td rowspan="3">
                        <label><?php _e( 'Value(s)', 'woocommerce' ); ?>:</label>
                        <textarea name="attribute_values[]" cols="5" rows="5" placeholder="<?php _e( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ); ?>"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><input type="checkbox" class="checkbox" name="attribute_visibility[]" value="1" /> <?php _e( 'Visible on the product page', 'woocommerce' ); ?></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="enable_variation show_if_variable">
                            <label><input type="checkbox" class="checkbox" name="attribute_variation[]" value="1" /> <?php _e( 'Used for variations', 'woocommerce' ); ?></label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
}

function bf_wc_attrebutes_save($post_id){
xdebug_break();
    $attributes = array();

    if ( isset( $_POST['attribute_names'] ) && isset( $_POST['attribute_values'] ) ) {

        $attribute_names  = $_POST['attribute_names'];
        $attribute_values = $_POST['attribute_values'];

        if ( isset( $_POST['attribute_visibility'] ) ) {
            $attribute_visibility = $_POST['attribute_visibility'];
        }

        if ( isset( $_POST['attribute_variation'] ) ) {
            $attribute_variation = $_POST['attribute_variation'];
        }

        $attribute_is_taxonomy = $_POST['attribute_is_taxonomy'];
        $attribute_position    = $_POST['attribute_position'];
        $attribute_names_count = sizeof( $attribute_names );

        for ( $i = 0; $i < $attribute_names_count; $i++ ) {

            if ( ! $attribute_names[ $i ] ) {
                continue;
            }

            $is_visible   = isset( $attribute_visibility[ $i ] ) ? 1 : 0;
            $is_variation = isset( $attribute_variation[ $i ] ) ? 1 : 0;
            $is_taxonomy  = $attribute_is_taxonomy[ $i ] ? 1 : 0;

            if ( $is_taxonomy ) {

                if ( isset( $attribute_values[ $i ] ) ) {

                    // Select based attributes - Format values (posted values are slugs)
                    if ( is_array( $attribute_values[ $i ] ) ) {
                        $values = array_map( 'sanitize_title', $attribute_values[ $i ] );

                        // Text based attributes - Posted values are term names - don't change to slugs
                    } else {
                        $values = array_map( 'stripslashes', array_map( 'strip_tags', explode( WC_DELIMITER, $attribute_values[ $i ] ) ) );
                    }

                    // Remove empty items in the array
                    $values = array_filter( $values, 'strlen' );

                } else {
                    $values = array();
                }

                // Update post terms
                if ( taxonomy_exists( $attribute_names[ $i ] ) ) {
                    wp_set_object_terms( $post_id, $values, $attribute_names[ $i ] );
                }

                if ( $values ) {
                    // Add attribute to array, but don't set values
                    $attributes[ sanitize_title( $attribute_names[ $i ] ) ] = array(
                        'name'         => wc_clean( $attribute_names[ $i ] ),
                        'value'        => '',
                        'position'     => $attribute_position[ $i ],
                        'is_visible'   => $is_visible,
                        'is_variation' => $is_variation,
                        'is_taxonomy'  => $is_taxonomy
                    );
                }

            } elseif ( isset( $attribute_values[ $i ] ) ) {

                // Text based, separate by pipe
                $values = implode( ' ' . WC_DELIMITER . ' ', array_map( 'wc_clean', explode( WC_DELIMITER, $attribute_values[ $i ] ) ) );

                // Custom attribute - Add attribute to array and set the values
                $attributes[ sanitize_title( $attribute_names[ $i ] ) ] = array(
                    'name'         => wc_clean( $attribute_names[ $i ] ),
                    'value'        => $values,
                    'position'     => $attribute_position[ $i ],
                    'is_visible'   => $is_visible,
                    'is_variation' => $is_variation,
                    'is_taxonomy'  => $is_taxonomy
                );
            }

        }
    }

    if ( ! function_exists( 'attributes_cmp' ) ) {
        function attributes_cmp( $a, $b ) {
            if ( $a['position'] == $b['position'] ) {
                return 0;
            }

            return ( $a['position'] < $b['position'] ) ? -1 : 1;
        }
    }
    uasort( $attributes, 'attributes_cmp' );

    update_post_meta( $post_id, '_product_attributes', $attributes );

}
