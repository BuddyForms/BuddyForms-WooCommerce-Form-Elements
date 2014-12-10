<?php

function buddyforms_woocommerce_create_frontend_form_element($form, $form_args){
global $thepostid, $post;

    extract($form_args);

    $thepostid          = $post_id;
    $post               = get_post($post_id);
    $buddyforms_options = get_option('buddyforms_options');

    switch ($customfield['type']) {

        case 'General':
            $form->addElement(  new Element_HTML('<div id="woocommerce-product-data" class="form-field ">'));
            $product_type_default = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default']))
                $product_type_default = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default'];

            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden'])){

                $form->addElement( new Element_Hidden($customfield['slug'], $product_type_default));

            } else {

                ob_start();
                    bf_wc_product_type($post_id, $customfield);
                    $attr_test = ob_get_contents();
                ob_clean();

                $form->addElement(  new Element_HTML($attr_test));

                $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));

                ob_start();
                bf_wc_downloadable($post_id, $customfield);

                $attr_test = ob_get_contents();
                ob_clean();

                $form->addElement(  new Element_HTML($attr_test) );

            }

            ob_start();
                bf_wc_product_general($post_id, $customfield);
                $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test) );
            $form->addElement(  new Element_HTML('</div>'));
            break;

        case 'Inventory':

            ob_start();
                bf_wc_product_inventory($post_id, $customfield);
                $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test) );

            break;
        case 'Shipping':

            ob_start();
                bf_wc_shipping($post_id, $customfield);
                $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));

            break;
        case 'Linked-Products':

            ob_start();
                bf_wc_product_linked($post_id, $customfield);
                $attr_test = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($attr_test));

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

