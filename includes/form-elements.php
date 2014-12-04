<?php

function buddyforms_woocommerce_create_frontend_form_element($form, $form_args){

    extract($form_args);

    $buddyforms_options = get_option('buddyforms_options');

    switch ($customfield['type']) {

        case 'Product-Type':

            $product_type_default = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default']))
                $product_type_default = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default'];

            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden'])){
                $form->addElement( new Element_Hidden($customfield['slug'], $product_type_default));
            } else {
                $args = array(
                    'hide_empty'        => 0,
                    'id'                => $customfield['slug'],
                    'child_of'          => 0,
                    'echo'              => FALSE,
                    'selected'          => false,
                    'hierarchical'      => 1,
                    'name'              => $customfield['slug'] . '[]',
                    'class'             => 'postform chosen',
                    'depth'             => 0,
                    'tab_index'         => 0,
                    'taxonomy'          => 'product_type',
                    'hide_if_empty'     => FALSE,
                );

                $dropdown = wp_dropdown_categories($args);

                $the_post_terms = get_the_terms( $post_id, $customfield['slug'] );

                if (is_array($the_post_terms)) {
                    foreach ($the_post_terms as $key => $post_term) {
                        $dropdown = str_replace(' value="' . $post_term->term_id . '"', ' value="' . $post_term->term_id . '" selected="selected"', $dropdown);
                    }
                    //$dropdown = str_replace(' value="' . $customfield_val . '"', ' value="' . $customfield_val . '" selected="selected"', $dropdown);
                } else {
                    $dropdown = str_replace(' value="' . $product_type_default . '"', ' value="' . $product_type_default . '" selected="selected"', $dropdown);
                }


                $required = '';
                if(isset($customfield['required']) && is_array( $customfield['required'] )){
                    $required = '<span class="required">* </span>';
                }
                $dropdown = '<div class="bf_field_group">
                            <label for="editpost-element-' . $field_id . '">
                                '.$required.$customfield['name'] . ':
                            </label>
                            <div class="bf_inputs">' . $dropdown . ' </div>

                        </div>';

                $form->addElement( new Element_HTML($dropdown));

            }

            break;
        case 'Virtual':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));


            ob_start();
                bf_wc_product_type($post_id, $customfield);
            //$post = get_post($post_id);
            //WC_Meta_Box_Product_Data::output($post);
            $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));


            break;
        case 'Downloadable':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));

            ob_start();
            bf_wc_downloadable($post_id, $customfield);

            $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));



            break;
        case 'SKU':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Textbox($customfield['name'], $customfield['slug'], $element_attr));
            break;
        case 'Regular-Price':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Number($customfield['name'], $customfield['slug'], $element_attr));
            break;
        case 'Sale-Price':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Number($customfield['name'], $customfield['slug'], $element_attr));
            break;
        case 'Sale-Price-Dates':

            $customfield_val = get_post_meta($post_id, '_sale_price_dates_from', true);
            $customfield_val = date_i18n( 'Y-m-d', (int)$customfield_val );
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input bf_datetime', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array( 'value' => $customfield_val, 'class' => 'settings-input bf_price_date', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Textbox('Sale Price Date From', '_sale_price_dates_from', $element_attr));

            $customfield_val = get_post_meta($post_id, '_sale_price_dates_to', true);
            $customfield_val = date_i18n( 'Y-m-d', (int)$customfield_val );
            $element_attr = isset($customfield['required']) ? array( 'required' => true, 'value' => $customfield_val, 'class' => 'settings-input bf_price_date', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array( 'value' => $customfield_val, 'class' => 'settings-input bf_price_date', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Textbox('Sale Price Date To', '_sale_price_dates_to', $element_attr));


            $form->addElement(new Element_HTML('<div class="bf_datetime_wrap">'));
            $form->addElement(new Element_Textbox('Schedule Time', 'schedule', $element_attr));
            $form->addElement(new Element_HTML('</div>'));
            break;
        case 'Manage-stock':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));

            $customfield_val = get_post_meta($post_id, '_stock', true);
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Number('Stock Qty', '_stock', $element_attr));

            $customfield_val = get_post_meta($post_id, '_backorders', true);
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Select('Allow Backorders?', '_backorders', array('no' => 'Do not allow', 'notify' => 'Allow, but notify customer', 'yes' => 'Allow'), $element_attr));

            break;
        case 'Stock-status':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '') : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  isset( $customfield['description'] ) ? $customfield['description'] : '');
            $form->addElement( new Element_Select($customfield['name'], $customfield['slug'], array('instock' => 'In stock', 'outofstock' => 'Out of stock'), $element_attr));
            break;
        case 'Sold-Individually':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));
            break;
        case 'Shipping':

            ob_start();
            bf_wc_shipping($post_id, $customfield);
            $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));

            break;
        case 'Up-Sells':
            $form->addElement( new Element_HTML('<select id="upsell_ids" name="upsell_ids[]" class=" chosen" multiple="multiple" data-placeholder="Search for a product&hellip">'));

            $upsell_ids = get_post_meta( $post_id, '_upsell_ids', true );
            $product_ids = ! empty( $upsell_ids ) ? array_map( 'absint',  $upsell_ids ) : null;

            if ( $product_ids ) {

                foreach ( $product_ids as $product_id ) {

                    $product = wc_get_product( $product_id );

                    if ( $product ) {
                        $form->addElement(  new Element_HTML('<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( $product->get_formatted_name() ) . '</option>'));
                    }
                }
            }

            $form->addElement(  new Element_HTML('</select>'));

            break;
        case 'Attributes':

            ob_start();
            bf_wc_attrebutes_custom($post_id, $customfield);
            $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));

            break;

        case 'Product-Gallery':
            ob_start();
            $post = get_post($post_id);
            WC_Meta_Box_Product_Images::output($post);
            $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));

            break;

    }

    return $form;

}
add_filter('buddyforms_create_edit_form_display_element','buddyforms_woocommerce_create_frontend_form_element',1,2);

