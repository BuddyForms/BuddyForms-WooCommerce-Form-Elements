<?php

add_action('buddyforms_update_post_meta', 'buddyforms_woocommerce_updtae_post_meta', 99, 2);
function buddyforms_woocommerce_updtae_post_meta($customfield, $post_id){
    global $bf_wc_save_meta, $bf_wc_save_gallery;

    if( $customfield['type'] == 'General' )
        $bf_wc_save_meta = 'yes';


    if( $customfield['type'] == 'Product-Gallery')
        $bf_wc_save_gallery = 'yes';

}

add_action('buddyforms_after_save_post', 'buddyforms_woocommerce_updtae_wc_post_meta', 99, 1);
function buddyforms_woocommerce_updtae_wc_post_meta($post_id){
    global $post, $bf_wc_save_gallery, $bf_wc_save_meta;

    if($bf_wc_save_meta == 'yes')
        WC_Meta_Box_Product_Data::save($post_id,$post);

    if($bf_wc_save_gallery == 'yes')
        WC_Meta_Box_Product_Images::save($post_id, $post);

}

