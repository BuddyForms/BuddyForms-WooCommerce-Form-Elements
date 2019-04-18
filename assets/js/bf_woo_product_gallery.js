/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */


jQuery(function ($) {
    $('.add_product_images a').text(product_gallery_param.button_text);
    var product_image_gallery_field = jQuery('#product_image_gallery');
    if(product_gallery_param.required && product_gallery_param.required[0]){
        if(product_gallery_param.required[0] === "required"){

            product_image_gallery_field.attr("required", true);
            var formSlug = jQuery('input[name="form_slug"]');
            var buddyformsForm = jQuery("form[id='buddyforms_form_" + formSlug.val() + "']");
            var validator = jQuery( buddyformsForm ).validate();
            var prev_ignore = validator.settings.ignore;
            var new_ignore = prev_ignore.replace(":hidden", "").trim();
            var strnew = new_ignore.substr(0, new_ignore.length-1);
            validator.settings.ignore =""+strnew;
            validator.element( "#product_image_gallery" );

        }
    }

});


