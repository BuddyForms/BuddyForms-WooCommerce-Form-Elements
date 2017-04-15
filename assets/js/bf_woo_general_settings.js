jQuery(document).ready(function (data) {
	//REGULAR PRICE
	if (general_settings_param.product_regular_price && general_settings_param.product_regular_price[0]) {
		var regularPrice = general_settings_param.product_regular_price[0];
		if (regularPrice === "required") {
			jQuery("#_regular_price").attr("required", true);
		}
	}

	//SALES PRICE
	if(general_settings_param.product_sales_price) {
		var salesPrice = general_settings_param.product_sales_price;
		if (salesPrice === "required") {
			jQuery("#_sale_price").attr("required", true);
		}
		else if (salesPrice === "hidden") {
			jQuery("#_sale_price").hide();
			jQuery("label[for='_sale_price']").hide();
		}
	}

	//SKU
	if(general_settings_param.product_sku) {
		var sku = general_settings_param.product_sku;
		if (sku === "required") {
			jQuery("#_sku").attr("required", true);
		}
		else if (sku === "hidden") {
			jQuery("._sku_field").hide();
		}
	}

	// PRODUCT SHIPPING
	if (general_settings_param.product_shipping_hidden && general_settings_param.product_shipping_hidden[0] && general_settings_param.product_shipping_hidden !== undefined) {
		var shipping = general_settings_param.product_shipping_hidden[0];
		if (shipping === "hidden") {
			jQuery(".shipping_options").addClass('show_if_virtual');
			if (general_settings_param.product_shipping_hidden_weight) {
				var height = general_settings_param.product_shipping_hidden_weight;
				jQuery("#_weight").val(height);
			}
			if (general_settings_param.product_shipping_hidden_dimension_height) {
				var dimesion_height = general_settings_param.product_shipping_hidden_dimension_height;
				jQuery("input[name='_height']").val(dimesion_height);
			}
			if (general_settings_param.product_shipping_hidden_dimension_width) {
				var dimension_width = general_settings_param.product_shipping_hidden_dimension_width;
				jQuery("input[name='_width']").val(dimension_width);
			}
			if (general_settings_param.product_shipping_hidden_dimension_length) {
				var dimension_long = general_settings_param.product_shipping_hidden_dimension_length;
				jQuery("#product_length").val(dimension_long);
			}
		}
	}
	//LINKED PRODUCTS
	if (general_settings_param.product_cross_sales && general_settings_param.product_cross_sales[0] && general_settings_param.product_cross_sales !== undefined) {
		var linked_product = general_settings_param.product_cross_sales[0];
		if (linked_product === "hidden") {
			jQuery("#crosssell_ids").parent("p.form-field").hide();
		}

	}
	if (general_settings_param.product_up_sales && general_settings_param.product_up_sales !== undefined) {
		var up_sales = general_settings_param.product_up_sales[0];
		if (up_sales === "hidden") {
			jQuery("#upsell_ids").parent("p.form-field").hide();
		}
	}

	//INVENTORY
	if (general_settings_param.product_sold_individually_options && general_settings_param.product_sold_individually_options[0] && general_settings_param.product_sold_individually_options !== undefined) {
		var sold_individually = general_settings_param.product_sold_individually_options[0];
		if (sold_individually === "hidden") {
			jQuery("._sold_individually_field").addClass('hide_if_simple');
		}
	}

	if (general_settings_param.product_stock_status_options && general_settings_param.product_stock_status_options[0] && general_settings_param.product_stock_status_options !== undefined) {
		var in_stock = general_settings_param.product_stock_status_options[0];
		if (in_stock === "hidden") {
			jQuery("._stock_status_field").addClass('hide_if_simple');
		}
	}

	if (general_settings_param.product_manage_stock && general_settings_param.product_manage_stock[0] && general_settings_param.product_manage_stock !== undefined) {
		var manage_stock = general_settings_param.product_manage_stock[0];
		if (manage_stock === "manage") {
			jQuery("._manage_stock_field").addClass('hide_if_simple');
			if (general_settings_param.product_manage_stock_qty) {
				var amount = general_settings_param.product_manage_stock_qty;
				jQuery("#_stock").val(amount);
			}
			if (general_settings_param.product_allow_backorders) {
				var backorder = general_settings_param.product_allow_backorders;
				jQuery('#_backorders').find('option:selected').remove();
				jQuery("#_backorders option[value='" + backorder + "']").attr('selected', 'selected');
			}
		}
	}

});
