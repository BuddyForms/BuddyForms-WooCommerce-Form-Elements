/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

jQuery(function ($) {
    var addProductImageText = (product_gallery_param.button_text)? product_gallery_param.button_text: 'Add product gallery images';
    $('.add_product_images a').text(addProductImageText);
    var product_image_gallery_field = jQuery('#product_image_gallery');
    if(product_gallery_param.required && product_gallery_param.required[0]){
        if(product_gallery_param.required[0] === "required"){
            $.validator.setDefaults({
                ignore: ".ignore" // validate all hidden select elements
            });
            var formSlug = jQuery('input[name="form_slug"]').val();
            product_image_gallery_field.attr("required", true);
            product_image_gallery_field.attr("data-form", formSlug);
        }
    }
});

