/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */


jQuery(document).ready(function ($) {

    jQuery("form").validate();
    jQuery.validator.addMethod("regular-price", function (value, element) {
        var match =  /^\d+\.?\d{0,2}$/.test(value);
        if(match){
            return true;
        }
        return false;
    }, "Please enter a number  in decimal format (.) without thousands separators and 2 decimal places.");
    jQuery.validator.addMethod("sales-price", function (value, element) {
        var match =  /^\d+\.?\d{0,2}$/.test(value);
        if(match){
            return true;
        }
        return false;
    }, "Please enter a number  in decimal format (.) without thousands separators and 2 decimal places.");
    jQuery('#sortable_buddyforms_elements li.bf_woocommerce ').each(function () {
        var field_row = jQuery(this),
            field_row_id = field_row.attr('id'),
            field_id = field_row_id.replace('field_', ''),
            virtual_row = field_row.find("#table_row_" + field_id + "_virtual"),
            booking_has_person_row = field_row.find("#table_row_" + field_id + "_wc_booking_has_persons"),
            booking_has_resources_row = field_row.find("#table_row_" + field_id + "_wc_booking_has_resources"),
            downloadable_row = field_row.find("#table_row_" + field_id + "_downloadable"),
            regular_price_row = field_row.find("#table_row_" + field_id + "_product_regular_price"),
            regular_price_amount_row = field_row.find("#table_row_" + field_id + "_regular_price_amount"),
            sales_price_row = field_row.find("#table_row_" + field_id + "_product_sales_price"),
            sales_price_amount_row = field_row.find("#table_row_" + field_id + "_sales_price_amount"),
            price_date_row = field_row.find("#table_row_" + field_id + "_product_sales_price_dates"),
            price_start_date_row = field_row.find("#table_row_" + field_id + "_product_sales_start_date"),
            price_end_date_row = field_row.find("#table_row_" + field_id + "_product_sales_end_date"),
            download_name_row = field_row.find("#table_row_" + field_id + "_download_name"),
            download_url_row = field_row.find("#table_row_" + field_id + "_download_url"),
            download_limit_row = field_row.find("#table_row_" + field_id + "_download_limit"),
            download_expiry_row = field_row.find("#table_row_" + field_id + "_download_expiry"),
            sku_value_row = field_row.find("#table_row_" + field_id + "_sku_value"),
            stock_status_value_row = field_row.find("#table_row_" + field_id + "_product_stock_status"),
            sold_individually_value_row = field_row.find("#table_row_" + field_id + "_product_sold_individually"),

            virtual = field_row.find("#_virtual-0"),
            booking_has_person = field_row.find("#_wc_booking_has_persons-0"),
            booking_has_resources = field_row.find("#_wc_booking_has_resources-0"),
            regular_price = field_row.find("#product_regular_price_" + field_id),
            regular_price_amount = field_row.find("#"+field_id+"_regular_price_amount"),
            sales_price_amount = field_row.find("#"+field_id+"_sales_price_amount"),
            sale_price = field_row.find("#product_sales_price_" + field_id),
            price_date = field_row.find("#product_sales_price_dates_" + field_id),
            price_start_date = field_row.find("#product_sales_start_date_" + field_id),
            price_end_date = field_row.find("#product_sales_end_date_" + field_id),
            downloadable = field_row.find("#_downloadable-0"),
            download_name = field_row.find("#" + field_id + "_download_name"),
            download_url = field_row.find("#" + field_id + "_download_url"),
            download_limit = field_row.find("#" + field_id + "_download_limit"),
            download_expiry = field_row.find("#" + field_id + "_download_expiry"),
            sku_value = field_row.find("#" + field_id + "_sku_value"),
            stock_status_value = field_row.find("#" + field_id + "_product_stock_status"),
            sold_individually_value = field_row.find("#" + field_id + "_product_sold_individually")
        ;

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_type_default]"]').change(function () {
            if (field_row.find('#product_type_hidden-0').is(':checked')) {
                var product_type = $(this).val();
                switch (product_type) {
                    case 'simple':
                        virtual_row.show();
                        downloadable_row.show();
                        virtual.parent().show();
                        downloadable.parent().show();

                        regular_price_row.show();
                        regular_price.show();

                        sales_price_row.show();
                        sale_price.show();

                        price_date_row.show();
                        price_date.show();

                        break;
                    case 'booking':
                        virtual_row.show();
                        virtual.parent().show();
                        virtual.show();
                        downloadable_row.hide();

                        download_name_row.hide();
                        download_url_row.hide();
                        download_limit_row.hide();
                        download_expiry_row.hide();

                        regular_price_row.hide();
                        sales_price_row.hide();
                        price_date_row.hide();
                        break;
                    default:
                        virtual_row.hide();
                        downloadable_row.hide();
                }
                virtual.attr('checked', false).change();
                downloadable.attr('checked', false).change();
                downloadable.prop('checked', false);
            }
        });
        $('input[name="buddyforms_options[form_fields][' + field_id + '][product_type_hidden][]"]').click(function () {
            if (!$(this).is(':checked')) {
                virtual_row.removeAttr('style');
                downloadable_row.removeAttr('style');
                booking_has_person_row.removeAttr('style');
                booking_has_resources_row.removeAttr('style');
            } else {
                var product_type = $("#product-type").val();
                switch (product_type) {
                    case 'simple':
                        virtual_row.show();
                        downloadable_row.show();

                        virtual.parent().show();
                        virtual.show();

                        downloadable.parent().show();
                        downloadable.show();

                        booking_has_person_row.hide();
                        booking_has_resources_row.hide();

                        download_name_row.show();
                        download_url_row.show();
                        download_limit_row.show();
                        download_expiry_row.show();
                        break;
                    case 'booking':
                        download_name_row.hide();
                        download_url_row.hide();
                        download_limit_row.hide();
                        download_expiry_row.hide();
                        booking_has_person_row.show();
                        booking_has_resources_row.show();

                        booking_has_person.parent().show();
                        booking_has_resources.parent().show();

                        booking_has_person.show();
                        booking_has_resources.show();

                        virtual_row.show();
                        virtual.parent().show();
                        virtual.show();

                        downloadable_row.hide();
                        break;
                    default:
                        booking_has_person_row.hide();
                        booking_has_resources_row.hide();
                        virtual_row.hide();
                        downloadable_row.hide();

                }
            }
            virtual.attr('checked', false).change();
            virtual.prop('checked', false);
            downloadable.attr('checked', false).change();
            downloadable.prop('checked', false);


        });

        $('input[name="buddyforms_options[form_fields][' + field_id + '][product_tax_hidden][]"]').click(function () {
            if (!$(this).is(':checked')) {
                $('#table_row_' + field_id + '_product_tax_status_default').addClass('hidden');
                $('#table_row_' + field_id + '_product_tax_class_default').addClass('hidden');
            } else {
                $('#table_row_' + field_id + '_product_tax_status_default').removeClass('hidden');
                $('#table_row_' + field_id + '_product_tax_class_default').removeClass('hidden');
                $('#product_tax_status_default').removeClass('hidden');
                $('#product_tax_class_default').removeClass('hidden');
            }
        });
        $('input[name="buddyforms_options[form_fields][' + field_id + '][product_type_options][_downloadable][]"]').click(function () {
            if (!$(this).is(':checked')) {
                download_name_row.hide();
                download_url_row.hide();
                download_limit_row.hide();
                download_expiry_row.hide();
            } else {

                download_name_row.show();
                download_url_row.show();
                download_limit_row.show();
                download_expiry_row.show();

                download_name.show();
                download_url.show();
                download_limit.show();
                download_expiry.show();

            }
        });

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_sku]"]').change(function () {
            if ($(this).val() === 'hidden') {

                sku_value_row.show();
                sku_value.show();
                sku_value_row.parent().show();

            }
            else{
                sku_value_row.hide();
            }
        });

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_sold_individually_options]"]').change(function () {
            if ($(this).val() === 'hidden') {

                sold_individually_value_row.show();
                sold_individually_value.show();
                sold_individually_value.parent().show();

            }
            else{
                sold_individually_value_row.hide();
            }
        });

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_stock_status_options]"]').change(function () {
            if ($(this).val() === 'hidden') {

                stock_status_value_row.show();
                stock_status_value.show();
                stock_status_value.parent().show();

            }
            else{
                stock_status_value_row.hide();
            }
        });

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_regular_price]"]').change(function () {
            if ($(this).val() === 'hidden') {

                regular_price_amount_row.show();
                regular_price_amount.show();

            }
            else{
                regular_price_amount_row.hide();
            }
        });

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_sales_price]"]').change(function () {
            if ($(this).val() === 'hidden') {
                sales_price_amount_row.show();
                sales_price_amount.show();
            }
            else{
                sales_price_amount_row.hide();
            }
        });

        $('select[name="buddyforms_options[form_fields][' + field_id + '][product_sales_price_dates]"]').change(function () {
            
            if ($(this).val() === 'hidden') {

                price_start_date_row.show();
                price_end_date_row.show();
                price_start_date.show();
                price_end_date.show();
            }
            else{
                price_start_date_row.hide();
                price_end_date_row.hide();
            }

        });

    });
});