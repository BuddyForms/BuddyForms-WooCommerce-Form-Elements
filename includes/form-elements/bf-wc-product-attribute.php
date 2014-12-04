<?php

function bf_wc_attrebutes_custom($thepostid, $customfield){ ?>


    <div id="product_attributes" class="panel wc-metaboxes-wrapper">

    <p class="toolbar">
        <a href="#" class="close_all"><?php _e( 'Close all', 'woocommerce' ); ?></a><a href="#" class="expand_all"><?php _e( 'Expand all', 'woocommerce' ); ?></a>
    </p>

    <div class="product_attributes wc-metaboxes">

        <?php

//        echo '<pre>';
//        print_r($customfield);
//        echo '</pre>';

        // Array of defined attribute taxonomies
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        // Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set
        $attributes = maybe_unserialize( get_post_meta( $thepostid, '_product_attributes', true ) );
        $form_slug = get_post_meta( $thepostid, '_bf_form_slug', true );

        $i = -1;

        // Taxonomies
        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {

                // Get name of taxonomy we're now outputting (pa_xxx)
                $attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );

                // Ensure it exists
                if ( ! taxonomy_exists( $attribute_taxonomy_name ) ) {
                    continue;
                }

                $i++;

                // Get product data values for current taxonomy - this contains ordering and visibility data
                if ( isset( $attributes[ sanitize_title( $attribute_taxonomy_name ) ] ) ) {
                    $attribute = $attributes[ sanitize_title( $attribute_taxonomy_name ) ];
                }

                $position = empty( $attribute['position'] ) ? 0 : absint( $attribute['position'] );

                // Get terms of this taxonomy associated with current product
                $post_terms = wp_get_post_terms( $thepostid, $attribute_taxonomy_name );

                // Any set?
                $has_terms = ( is_wp_error( $post_terms ) || ! $post_terms || sizeof( $post_terms ) == 0 ) ? 0 : 1;
                ?>
                <div class="woocommerce_attribute wc-metabox closed taxonomy <?php echo $attribute_taxonomy_name; ?>" rel="<?php echo $position; ?>" <?php if ( ! $has_terms ) echo 'style="display:none"'; ?>>
                    <h3>
                        <button type="button" class="remove_row button"><?php _e( 'Remove', 'woocommerce' ); ?></button>
                        <div class="handlediv" title="<?php _e( 'Click to toggle', 'woocommerce' ); ?>"></div>
                        <strong class="attribute_name"><?php echo apply_filters( 'woocommerce_attribute_label', $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name, $tax->attribute_name ); ?></strong>
                    </h3>
                    <table cellpadding="0" cellspacing="0" class="woocommerce_attribute_data wc-metabox-content">
                        <tbody>
                        <tr>
                            <td class="attribute_name">
                                <label><?php _e( 'Name', 'woocommerce' ); ?>:</label>
                                <strong><?php echo $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name; ?></strong>

                                <input type="hidden" name="attribute_names[<?php echo $i; ?>]" value="<?php echo esc_attr( $attribute_taxonomy_name ); ?>" />
                                <input type="hidden" name="attribute_position[<?php echo $i; ?>]" class="attribute_position" value="<?php echo esc_attr( $position ); ?>" />
                                <input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="1" />
                            </td>
                            <td rowspan="3">
                                <label><?php _e( 'Value(s)', 'woocommerce' ); ?>:</label>
                                <?php if ( 'select' == $tax->attribute_type ) : ?>
                                    <select multiple="multiple" data-placeholder="<?php _e( 'Select terms', 'woocommerce' ); ?>" class="multiselect attribute_values" name="attribute_values[<?php echo $i; ?>][]">
                                        <?php
                                        $all_terms = get_terms( $attribute_taxonomy_name, 'orderby=name&hide_empty=0' );
                                        if ( $all_terms ) {
                                            foreach ( $all_terms as $term ) {
                                                $has_term = has_term( (int) $term->term_id, $attribute_taxonomy_name, $thepostid ) ? 1 : 0;
                                                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $has_term, 1, false ) . '>' . $term->name . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>

                                    <button class="button plus select_all_attributes"><?php _e( 'Select all', 'woocommerce' ); ?></button> <button class="button minus select_no_attributes"><?php _e( 'Select none', 'woocommerce' ); ?></button>

                                    <button class="button fr plus add_new_attribute" data-attribute="<?php echo $attribute_taxonomy_name; ?>"><?php _e( 'Add new', 'woocommerce' ); ?></button>

                                <?php elseif ( 'text' == $tax->attribute_type ) : ?>
                                    <input type="text" name="attribute_values[<?php echo $i; ?>]" value="<?php

                                    // Text attributes should list terms pipe separated
                                    if ( $post_terms ) {
                                        $values = array();
                                        foreach ( $post_terms as $term )
                                            $values[] = $term->name;
                                        echo esc_attr( implode( ' ' . WC_DELIMITER . ' ', $values ) );
                                    }

                                    ?>" placeholder="<?php _e( 'Pipe (|) separate terms', 'woocommerce' ); ?>" />
                                <?php endif; ?>
                                <?php do_action( 'woocommerce_product_option_terms', $tax, $i ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><input type="checkbox" class="checkbox" <?php

                                    if ( isset( $attribute['is_visible'] ) ) {
                                        checked( $attribute['is_visible'], 1 );
                                    } else {
                                        checked( apply_filters( 'default_attribute_visibility', false, $tax ), true );
                                    }

                                    ?> name="attribute_visibility[<?php echo $i; ?>]" value="1" /> <?php _e( 'Visible on the product page', 'woocommerce' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="enable_variation show_if_variable">
                                    <label><input type="checkbox" class="checkbox" <?php

                                        if ( isset( $attribute['is_variation'] ) ) {
                                            checked( $attribute['is_variation'], 1 );
                                        } else {
                                            checked( apply_filters( 'default_attribute_variation', false, $tax ), true );
                                        }

                                        ?> name="attribute_variation[<?php echo $i; ?>]" value="1" /> <?php _e( 'Used for variations', 'woocommerce' ); ?></label>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php
            }
        }

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
<!--                        <div class="handlediv" title="--><?php //_e( 'Click to toggle', 'woocommerce' ); ?><!--"></div>-->
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
    </div>

    <?php  if ( isset($customfield['_bf_wc_attributes_pa']) || isset($customfield['attr_new_custom_field'])) { ?>
        <p class="toolbar">
            <button type="button" class="button button-primary add_attribute"><?php _e( 'Add', 'woocommerce' ); ?></button>
            <select name="attribute_taxonomy" class="attribute_taxonomy">

                <?php if(isset($customfield['attr_new_custom_field']))
                    echo '<option value="">' . __( 'Custom product attribute', 'woocommerce' ) .'</option>';

                if ( $attribute_taxonomies && isset($customfield['_bf_wc_attributes_pa'])) {

                    foreach ( $attribute_taxonomies as $tax ) {
                        if(in_array('pa_'.$tax->attribute_name, $customfield['_bf_wc_attributes_pa'])){
                            $attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                            $label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
                            echo '<option value="' . esc_attr( $attribute_taxonomy_name ) . '">' . esc_html( $label ) . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </p>
    <?php } ?>
    <?php do_action( 'woocommerce_product_options_attributes' ); ?>
    </div>

    <?php
}

function bf_wc_attrebutes_save($post_id){

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
