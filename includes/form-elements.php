<?php

function buddyforms_woocommerce_create_frontend_form_element($form, $form_args){
global $thepostid, $post;

    extract($form_args);

    if(!isset($customfield['type']))
        return $form;

    $thepostid          = $post_id;
    $post               = get_post($post_id);


    switch ($customfield['type']) {

        case 'WooCommerce':
            $form->addElement( new Element_HTML('<div id="woocommerce-product-data" class="form-field ">'));

                ob_start();
                    bf_wc_product_type($post_id, $customfield);
                    $get_contents = ob_get_contents();
                ob_clean();
                $form->addElement(  new Element_HTML($get_contents));

                ob_start();
                    bf_wc_product_general($post_id, $customfield);
                    $get_contents = ob_get_contents();
                ob_clean();
                $form->addElement(  new Element_HTML($get_contents) );

                ob_start();
                    bf_wc_downloadable($post_id, $customfield);
                    $get_contents = ob_get_contents();
                ob_clean();
                $form->addElement(  new Element_HTML($get_contents) );

//                ob_start();
//                    bf_wc_variations_custom($post_id, $customfield);
//                    $get_contents = ob_get_contents();
//                ob_clean();
//                $form->addElement(  new Element_HTML($get_contents) );

            $form->addElement(  new Element_HTML('</div>'));

            // Inventory

            ob_start();
                bf_wc_product_inventory($post_id, $customfield);
                $get_contents = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($get_contents) );

            // 'Shipping':

            ob_start();
                bf_wc_shipping($post_id, $customfield);
                $get_contents = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($get_contents));

            // Linked-Products':

            ob_start();
                bf_wc_product_linked($post_id, $customfield);
                $get_contents = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($get_contents));

            break;
        case 'Attributes':


            ob_start();
                bf_wc_attrebutes_custom($post_id, $customfield);
                $get_contents = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($get_contents));

            break;
        case 'Gallery':
            // Product Gallery

            ob_start();
            $post = get_post($post_id);
                WC_Meta_Box_Product_Images::output($post);
                $get_contents = ob_get_contents();
            ob_clean();

            $form->addElement(  new Element_HTML($get_contents));

            break;

    }

    return $form;

}
add_filter('buddyforms_create_edit_form_display_element','buddyforms_woocommerce_create_frontend_form_element',1 ,2);