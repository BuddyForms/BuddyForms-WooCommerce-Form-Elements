<?php

add_action('buddyforms_update_post_meta', 'buddyforms_woocommerce_updtae_post_meta', 99, 2);
function buddyforms_woocommerce_updtae_post_meta($customfield, $post_id){
    global $wc_save, $post;

    $wc_save = false;

    if( $customfield['type'] == 'Product-Type' ){

        if(isset($customfield['product_type_hidden'])) {
            $slug = Array();
            $term = get_term_by('id', $customfield['product_type_default'], $customfield['slug']);
            $slug[] = $term->slug;
            wp_set_post_terms($post_id, $slug, $customfield['slug'], false);
        } else {
            $taxonomy = get_taxonomy($customfield['slug']);

            if (isset($taxonomy->hierarchical) && $taxonomy->hierarchical == true) {

                if (isset($_POST[$customfield['slug']]))
                    $tax_item = $_POST[$customfield['slug']];

                if ($tax_item[0] == -1)
                    $tax_item[0] = $customfield['product_type_default'];

                wp_set_post_terms($post_id, $tax_item, 'product_type', false);
            } else {

                $slug = Array();

                if (isset($_POST[$customfield['slug']])) {
                    $postCategories = $_POST[$customfield['slug']];

                    foreach ($postCategories as $postCategory) {
                        $term = get_term_by('id', $postCategory, $customfield['slug']);
                        $slug[] = $term->slug;
                    }
                }

                wp_set_post_terms($post_id, $slug, $customfield['slug'], false);

            }
        }
    }
    if( $customfield['type'] == 'Manage-stock' ){
        update_post_meta($post_id, '_stock', $_POST['_stock'] );
        update_post_meta($post_id, '_backorders', $_POST['_backorders'] );
    }
    if( $customfield['type'] == 'Sale-Price-Dates' ){

        $sale_price_dates_from = wc_clean( $_POST['_sale_price_dates_from'] );
        $sale_price_dates_to = wc_clean( $_POST['_sale_price_dates_to'] );

        update_post_meta($post_id, '_sale_price_dates_from' , strtotime( $sale_price_dates_from ) );
        update_post_meta($post_id, '_sale_price_dates_to'   , strtotime( $sale_price_dates_to ) );
    }
    if( $customfield['type'] == 'Attributes'){
        $wc_save = true;
        WC_Meta_Box_Product_Data::save($post_id,$post);
    }
    if( $customfield['type'] == 'Product-Gallery'){
        $wc_save = true;
        WC_Meta_Box_Product_Images::save($post_id, $post);
    }
    if( $customfield['type'] == 'Linked-Products'){
        $wc_save = true;
        WC_Meta_Box_Product_Data::save($post_id,$post);
    }



}

add_action('buddyforms_after_save_post', 'buddyforms_woocommerce_updtae_wc_post_meta', 99, 1);
function buddyforms_woocommerce_updtae_wc_post_meta($post_id){
    global $post, $wc_save;
xdebug_break();
  //  if($wc_save == true)
        WC_Meta_Box_Product_Images::save($post_id, $post);

}

