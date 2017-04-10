jQuery( document ).ready( function( data ) {
	// $() will work as an alias for jQuery() inside of this function

	//REGULAR PRICE
	var regularPrice = general_settings_param.product_regular_price[0];
	if(regularPrice=="required"){
		jQuery("#_regular_price").attr("required", true);
	}

	//SALES PRICE
	var salesPrice = general_settings_param.product_sales_price;
	if(salesPrice =="required"){
		jQuery("#_sale_price").attr("required", true);
	}
	else if(salesPrice =="hidden"){
		jQuery("#_sale_price").hide();
		jQuery("label[for='_sale_price']").hide();
	}

	//SKU

	var sku = general_settings_param.product_sku;
	if(sku =="required"){
		jQuery("#_sku").attr("required", true);
	}
	else if(sku =="hidden"){
		jQuery("._sku_field").hide();

	}



} );
