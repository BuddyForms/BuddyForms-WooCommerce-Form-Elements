/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */
jQuery(document).ready(function ($) {
	jQuery('#sortable_buddyforms_elements li.bf_woocommerce ').each(function () {
		var field_row = jQuery(this),
			field_row_id = field_row.attr('id'),
			field_id = field_row_id.replace('field_', ''),
			virtual_row = field_row.find("#table_row_" + field_id + "_virtual"),
			booking_has_person_row = field_row.find("#table_row_" + field_id + "_wc_booking_has_persons"),
            booking_has_resources_row = field_row.find("#table_row_" + field_id + "_wc_booking_has_resources"),
			downloadable_row = field_row.find("#table_row_" + field_id + "_downloadable"),
			virtual = field_row.find("#_virtual-0"),
            booking_has_person = field_row.find("#_wc_booking_has_persons-0"),
            booking_has_resources = field_row.find("#_wc_booking_has_resources-0"),
			downloadable = field_row.find("#_downloadable-0");
		$('select[name="buddyforms_options[form_fields][' + field_id + '][product_type_default]"]').change(function () {
			if (field_row.find('#product_type_hidden-0').is(':checked')) {
				var product_type = $(this).val();
                switch(product_type) {
                    case 'simple':
                        virtual_row.show();
                        downloadable_row.show();
                        virtual.parent().show();
                        downloadable.parent().show();

                        booking_has_person_row.hide();
                        booking_has_resources_row.hide();
                        break;
                    case 'booking':
                        booking_has_person_row.show();
                        booking_has_resources_row.show();

                        booking_has_person.parent().show();
                        booking_has_resources.parent().show();

                        booking_has_person.show();
                        booking_has_resources.show();

                        virtual_row.show();
                        downloadable_row.hide();
                        break;
                    default:
                        booking_has_person_row.hide();
                        booking_has_resources_row.hide();
                        virtual_row.hide();
                        downloadable_row.hide();

                }
                virtual.attr('checked', false).change();
                downloadable.attr('checked', false).change();
                booking_has_person.attr('checked', false).change();
                booking_has_resources.attr('checked', false).change();

			}
		});
		$('input[name="buddyforms_options[form_fields][' + field_id + '][product_type_hidden][]"]').click(function () {
			if (!$(this).is(':checked')) {
				virtual_row.removeAttr('style');
				downloadable_row.removeAttr('style');
                booking_has_person_row.removeAttr('style');
                booking_has_resources_row.removeAttr('style');
			}else{

                var product_type = $("#product-type").val();
                switch(product_type) {
                    case 'simple':
                        virtual_row.show();
                        downloadable_row.show();

                        virtual.parent().show();
                        virtual.show();

                        downloadable.parent().show();
                        downloadable.show();

                        booking_has_person_row.hide();
                        booking_has_resources_row.hide();
                        break;
                    case 'booking':
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
		});
        $('input[name="buddyforms_options[form_fields][' + field_id + '][product_tax_hidden][]"]').click(function () {
            if (!$(this).is(':checked')) {
                $('#table_row_'+field_id+'_product_tax_status_default').addClass('hidden');
                $('#table_row_'+field_id+'_product_tax_class_default').addClass('hidden');
            }
            else{
                $('#table_row_'+field_id+'_product_tax_status_default').removeClass('hidden');
                $('#table_row_'+field_id+'_product_tax_class_default').removeClass('hidden');
                $('#product_tax_status_default').removeClass('hidden');
                $('#product_tax_class_default').removeClass('hidden');
			}
        });

		$('select[name="buddyforms_options[form_fields][' + field_id + '][product_sales_price]"]').change(function () {
			if ($(this).val() === 'hidden') {
				$('select[name="buddyforms_options[form_fields][' + field_id + '][product_sales_price_dates]"]').val('hidden');
			}
		});

		$('select[name="buddyforms_options[form_fields][' + field_id + '][product_sales_price_dates]"]').change(function () {
			var sale_prices = $('select[name="buddyforms_options[form_fields][' + field_id + '][product_sales_price]"]').val();
			if (sale_prices === 'hidden') {
				alert('Sales Price need to be different to Hidden!');
				$(this).val('hidden');
			}
		});

	});
});