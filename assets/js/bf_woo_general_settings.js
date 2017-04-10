jQuery(document).ready(function (data) {
	// $() will work as an alias for jQuery() inside of this function

	//REGULAR PRICE
	var regularPrice = general_settings_param.product_regular_price[0];
	if (regularPrice == "required") {
		jQuery("#_regular_price").attr("required", true);
	}

	//SALES PRICE
	var salesPrice = general_settings_param.product_sales_price;
	if (salesPrice == "required") {
		jQuery("#_sale_price").attr("required", true);
	}
	else if (salesPrice == "hidden") {
		jQuery("#_sale_price").hide();
		jQuery("label[for='_sale_price']").hide();
	}

	//SKU

	var sku = general_settings_param.product_sku;
	if (sku == "required") {
		jQuery("#_sku").attr("required", true);
	}
	else if (sku == "hidden") {
		jQuery("._sku_field").hide();

	}
	// PRODUCT SHIPPING

	if (general_settings_param.product_shipping_hidden != undefined) {
		var shipping = general_settings_param.product_shipping_hidden[0];
		if (shipping == "hidden") {
			jQuery(".shipping_options").addClass('show_if_virtual');
			var height = general_settings_param.product_shipping_hidden_weight;
			jQuery("#_weight").val(height);
			var dimesion_height =general_settings_param.product_shipping_hidden_dimension_height;
			jQuery("input[name='_height']").val(dimesion_height);
			var dimension_width = general_settings_param.product_shipping_hidden_dimension_width;
			jQuery("input[name='_width']").val(dimension_width);
			var dimension_long = general_settings_param.product_shipping_hidden_dimension_length;
			jQuery("#product_length").val(dimension_long);



		}
	}


});
