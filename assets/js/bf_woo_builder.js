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
			downloadable_row = field_row.find("#table_row_" + field_id + "_downloadable"),
			virtual = field_row.find("#_virtual-0"),
			downloadable = field_row.find("#_downloadable-0");
		$('select[name="buddyforms_options[form_fields][' + field_id + '][product_type_default]"]').change(function () {
			if (field_row.find('#product_type_hidden-0').is(':checked')) {
				var product_type = $(this).val();
				if (product_type === 'simple') {
					virtual_row.show();
					downloadable_row.show();
					virtual.parent().show();
					downloadable.parent().show();
				}
				else {
					virtual_row.hide();
					downloadable_row.hide();
					virtual.attr('checked', false).change();
					downloadable.attr('checked', false).change();
				}
			}
		});
		$('input[name="buddyforms_options[form_fields][' + field_id + '][product_type_hidden][]"]').click(function () {
			if (!$(this).is(':checked')) {
				virtual_row.removeAttr('style');
				downloadable_row.removeAttr('style');
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