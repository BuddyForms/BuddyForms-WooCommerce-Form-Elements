jQuery(function ($) {
	var select_product_type = jQuery('select#product-type'),
		virtual = jQuery('#_virtual'),
		downloadable = jQuery('#_downloadable');

	/**
	 * Determine wish tab will be default
	 *
	 * @param current_type
	 */
	function determine_default_tab(current_type) {
		if (current_type === 'grouped' || current_type === 'variable') {
			jQuery('li.general_tab').removeClass('active');
			jQuery('#general_product_data').hide();
			if (general_settings_param.product_manage_stock && general_settings_param.product_manage_stock[0] && general_settings_param.product_manage_stock[0] !== undefined) {
				jQuery('li.shipping_tab').addClass('active');
				jQuery('#shipping_product_data').show();
			}
			else {
				jQuery('li.inventory_tab').addClass('active');
				jQuery('#inventory_product_data').show();
			}
		}
	}

	function determine_when_is_required(current_type){

	}

	function set_default_option(){
		select_product_type.val('simple').change();
		virtual.attr('checked', false).change();
		downloadable.attr('checked', false).change();
	}

	//Set Product Type if they are hidden
	if (general_settings_param.product_type_hidden && general_settings_param.product_type_hidden[0] &&
		general_settings_param.product_type_hidden[0] === 'hidden') {
		//Set the prodcut type
		if (general_settings_param.product_type_default) {
			select_product_type.val(general_settings_param.product_type_default).change();
			determine_default_tab(general_settings_param.product_type_default);
		}
		//Set if is virtual or downloadable
		if (general_settings_param.product_type_options) {
			var virtual_val = (general_settings_param.product_type_options['_virtual'] !== undefined);
			var downloadable_val = (general_settings_param.product_type_options['_downloadable'] !== undefined);
			virtual.attr('checked', virtual_val).change();
			downloadable.attr('checked', downloadable_val).change();
		}
	}
	else {
		set_default_option();
		$('h2.hndle').show();
	}

	//Trigger if the product type if changed
	jQuery(document).on('woocommerce-product-type-change', function (obj, select_val) {
		console.log('mio ' + select_val);
	});



	//REGULAR PRICE
	if (general_settings_param.product_regular_price && general_settings_param.product_regular_price[0]) {
		var regularPrice = general_settings_param.product_regular_price[0];
		if (regularPrice === "required") {
			$("#_regular_price").attr("required", true);
		}
	}

	//SALES PRICE
	if (general_settings_param.product_sales_price) {
		var salesPrice = general_settings_param.product_sales_price;
		if (salesPrice === "required") {
			$("#_sale_price").attr("required", true);
		}
		else if (salesPrice === "hidden") {
			$("#_sale_price").hide();
			$("label[for='_sale_price']").hide();
		}
	}

	//SKU
	if (general_settings_param.product_sku) {
		var sku = general_settings_param.product_sku;
		if (sku === "required") {
			$("#_sku").attr("required", true);
		}
		else if (sku === "hidden") {
			$("._sku_field").hide();
		}
	}

	// PRODUCT SHIPPING
	if (general_settings_param.product_shipping_hidden && general_settings_param.product_shipping_hidden[0] && general_settings_param.product_shipping_hidden !== undefined) {
		var shipping = general_settings_param.product_shipping_hidden[0];
		if (shipping === "hidden") {
			$(".shipping_options").addClass('show_if_virtual');
			if (general_settings_param.product_shipping_hidden_weight) {
				var height = general_settings_param.product_shipping_hidden_weight;
				$("#_weight").val(height);
			}
			if (general_settings_param.product_shipping_hidden_dimension_height) {
				var dimesion_height = general_settings_param.product_shipping_hidden_dimension_height;
				$("input[name='_height']").val(dimesion_height);
			}
			if (general_settings_param.product_shipping_hidden_dimension_width) {
				var dimension_width = general_settings_param.product_shipping_hidden_dimension_width;
				$("input[name='_width']").val(dimension_width);
			}
			if (general_settings_param.product_shipping_hidden_dimension_length) {
				var dimension_long = general_settings_param.product_shipping_hidden_dimension_length;
				$("#product_length").val(dimension_long);
			}
		}
	}
	//LINKED PRODUCTS
	if (general_settings_param.product_cross_sales && general_settings_param.product_cross_sales[0] && general_settings_param.product_cross_sales !== undefined) {
		var linked_product = general_settings_param.product_cross_sales[0];
		if (linked_product === "hidden") {
			$("#crosssell_ids").parent("p.form-field").hide();
		}

	}
	if (general_settings_param.product_up_sales && general_settings_param.product_up_sales !== undefined) {
		var up_sales = general_settings_param.product_up_sales[0];
		if (up_sales === "hidden") {
			$("#upsell_ids").parent("p.form-field").hide();
		}
	}

	//INVENTORY
	if (general_settings_param.product_sold_individually_options && general_settings_param.product_sold_individually_options[0] && general_settings_param.product_sold_individually_options !== undefined) {
		var sold_individually = general_settings_param.product_sold_individually_options[0];
		if (sold_individually === "hidden") {
			$("._sold_individually_field").addClass('hide_if_simple');
		}
	}

	if (general_settings_param.product_stock_status_options && general_settings_param.product_stock_status_options[0] && general_settings_param.product_stock_status_options !== undefined) {
		var in_stock = general_settings_param.product_stock_status_options[0];
		if (in_stock === "hidden") {
			$("._stock_status_field").addClass('hide_if_simple');
		}
	}

	if (general_settings_param.product_manage_stock && general_settings_param.product_manage_stock[0] && general_settings_param.product_manage_stock[0] !== undefined) {
		var manage_stock = general_settings_param.product_manage_stock[0];
		if (manage_stock === "manage") {
			$("._manage_stock_field").addClass('hide_if_simple');
			$("._stock_field").hide();
			$("._backorders_field").hide();
			if (general_settings_param.product_manage_stock_qty) {
				var amount = general_settings_param.product_manage_stock_qty;
				$("#_stock").val(amount);
			}
			if (general_settings_param.product_allow_backorders) {
				var backorder = general_settings_param.product_allow_backorders;
				$('#_backorders').find('option:selected').remove();
				$("#_backorders option[value='" + backorder + "']").attr('selected', 'selected');
			}
		}
	}

});
