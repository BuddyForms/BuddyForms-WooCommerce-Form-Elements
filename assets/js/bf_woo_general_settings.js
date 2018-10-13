/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */
jQuery('#woocommerce-product-data').prepend(
	jQuery('<div>', {
		'class': 'woo_general_loader'
	}).text('loading...')
);
jQuery(function ($) {
    $( document ).ready(function() {

        var select_product_type = jQuery('select#product-type'),
            virtual = jQuery('#_virtual'),
            downloadable = jQuery('#_downloadable'),
            regular_price = jQuery('#_regular_price'),
            sale_price = jQuery('#_sale_price'),
            sale_price_dates_from = jQuery('#_sale_price_dates_from'),
            sale_price_dates_to = jQuery('#_sale_price_dates_to'),
            sku = jQuery('#_sku'),
            sold_individually = $("#_sold_individually"),
            manage_stock = $("#_manage_stock"),
            stock_status = $("#_stock_status"),
            stock = $("#_stock"),
            backorders = $("#_backorders"),
            weight = $("#_weight"),
            width = $("input[name='_width']"),
            height = $("input[name='_height']"),
            length = $("input[name='_length']"),
            shipping_class = $("#product_shipping_class"),
            tabs_hided = [],
            attribute_tab = $('.attribute_tab'),
            attribute_container = $('#product_attributes'),
            variations_tab = $('.variations_tab'),
            variations_container = $('#variable_product_options'),
            advanced_tab = jQuery('.advanced_tab'),
            advanced_container = jQuery('#advanced_product_data'),
            purchase_note = $('#_purchase_note'),
            menu_order_input = $('#menu_order'),
            reviews_allowed = $('#_reviews_allowed'),
            main_container = jQuery('#woocommerce-product-data'),
            val
        ;

        /**
         * Determine wish tab will be default
         *
         * @param current_type
         */
        function determine_default_tab(current_type) {
            var main_tab_set = false;
            jQuery('ul.wc-tabs li:visible').each(function () {
                var i, current = jQuery(this),
                    current_classes = current.attr('class').split(" ");
                for (i = 0; i < current_classes.length; i++) {
                    var searched_for_tab = jQuery.inArray(current_classes[i], tabs_hided);
                    if (searched_for_tab < 0) {
                        current.find('a').click();
                        main_tab_set = true;
                        return false;
                    }
                }
            });
            if (main_tab_set === false) {
                //Hide the entire container
                jQuery('#woocommerce-product-data').hide();
            }
        }

        function determine_when_is_required(current_type) {
            set_default_required();
            switch (current_type) {
                case 'simple':
                case 'external':
                    //REGULAR PRICE
                    if (general_settings_param.product_regular_price) {
                        var regular_price_opt = general_settings_param.product_regular_price;
                        if (regular_price_opt === "required") {
                            regular_price.attr("required", true);
                            if (general_settings_param.debug) console.log('Regular Price is required now');
                        }
                    }

                    //SALES PRICE
                    if (general_settings_param.product_sales_price) {
                        var sales_price_opt = general_settings_param.product_sales_price;
                        if (sales_price_opt === "required") {
                            sale_price.attr("required", true);
                            if (general_settings_param.debug) console.log('Sales Price is required now');
                        }
                    }

                    //SALES PRICE DATE
                    if (general_settings_param.product_sales_price_dates) {
                        var sales_price_date_opt = general_settings_param.product_sales_price_dates;
                        if (sales_price_date_opt === "required") {
                            sale_price_dates_from.attr("required", true);
                            sale_price_dates_to.attr("required", true);
                            $(".cancel_sale_schedule").hide();
                            $(".sale_schedule").hide();
                            $(".sale_price_dates_fields").show();
                            if (general_settings_param.debug) console.log('Sales Price Date is required now and showed');
                        }
                    }

                    break;
            }

            //SKU
            if (general_settings_param.product_sku) {
                var sku_option = general_settings_param.product_sku;
                if (sku_option === "required") {
                    sku.attr("required", true);
                    if (general_settings_param.debug) console.log('SKU is required');
                }
            }

        }

        function set_default_required() {
            regular_price.removeAttr('required');
            sale_price.removeAttr('required');
            sale_price_dates_from.removeAttr('required');
            sale_price_dates_to.removeAttr('required');
            sku.removeAttr('required');
            if (general_settings_param.debug) console.log('Set to default all required fields');
        }

        function set_default_option() {
            select_product_type.val('simple').change();
            virtual.attr('checked', false).change();
            downloadable.attr('checked', false).change();
            sold_individually.attr('checked', false).change();
            manage_stock.attr('checked', false).change();
            stock_status.val('instock').change();
            stock.val('');
            backorders.find("option:selected").removeAttr('selected');
            backorders.find("option[value='no']").attr('selected', 'selected');
            weight.val('');
            width.val('');
            height.val('');
            length.val('');
            shipping_class.val('-1').change();
            purchase_note.val('').change();
            menu_order_input.val('').change();
            reviews_allowed.attr('checked', false).change();
        }

        set_default_option();
        determine_default_tab();

        /**
         * This function is to keep compatibility with bootstrap theme in the front
         */
        function remove_hidden() {
            jQuery('#general_product_data,#inventory_product_data,#shipping_product_data,#linked_product_data,#product_attributes,#advanced_product_data,#variable_product_options').each(function () {
                jQuery(this).removeClass('hidden');
                jQuery(this).find('.hidden').removeClass('hidden');
            });
        }

        //Trigger if the product type if changed
        jQuery(document).on('woocommerce-product-type-change', function (obj, select_val) {
            determine_when_is_required(select_val);
            if (general_settings_param.debug) console.log('Product Type: ' + select_val);
        });

        remove_hidden();
        determine_when_is_required(select_product_type.val());

        //Remove not implemented tabs
        if (general_settings_param.disable_tabs) {
            jQuery.each(general_settings_param.disable_tabs, function (key, value) {
                if (general_settings_param[value] && general_settings_param[value][0] === 'hidden') {
                    jQuery.each(jQuery('.woo_element_span').find('#product-type').find('option'), function () {
                        if (value.indexOf(jQuery(this).val()) !== -1) {
                            jQuery(this).remove();
                        }
                    });
                    jQuery('.product_data_tabs.wc-tabs').find('.' + value + '_options.' + value + '_tab').remove();
                    jQuery('.product_data_tabs.wc-tabs').find('.' + value + '_tab').remove();
                    jQuery('#' + value).remove();
                }
            });
        }

        //region General Tab

        if (general_settings_param.product_regular_price && general_settings_param.product_sales_price && general_settings_param.product_sales_price_dates !== undefined) {

            var hide_general_proudct_type, hide_general_tax_field, hide_general_regular_price, hide_general_sales_price,
                hide_general_price_date = false;
            var product_type_default = null;
            if(general_settings_param.wc_tax_option_disabled){
                hide_general_tax_field = true;

            }else{
            //Set Product Tax if it is hidden
                if (general_settings_param.product_tax_hidden && general_settings_param.product_tax_hidden[0] &&
                    general_settings_param.product_tax_hidden[0] === 'hidden') {
                    hide_general_tax_field = true;
                    $('._tax_class_field').hide();
                    $('._tax_status_field').hide();

                    $('#_tax_status').val(general_settings_param.product_tax_status_default).change();
                    $('#_tax_class').val(general_settings_param.product_tax_class_default).change();

                }
            }

            //REGULAR PRICE
            if (general_settings_param.product_regular_price) {

                var regularPrice = general_settings_param.product_regular_price;
                if (regularPrice === "hidden") {
                    hide_general_regular_price = true;
                    regular_price.hide();
                    regular_price.parent().hide();
                }
            }

            //SALES PRICE
            if (general_settings_param.product_sales_price) {
                var salesPrice = general_settings_param.product_sales_price;
                if (salesPrice === "hidden") {
                    hide_general_sales_price = true;
                    sale_price.hide();
                    sale_price.parent().hide();
                }
            }

            //SALES PRICE DATE
            if (general_settings_param.product_sales_price_dates) {
                var sales_price_date_opt = general_settings_param.product_sales_price_dates;
                if (sales_price_date_opt === "hidden") {
                    hide_general_price_date = true;
                    sale_price_dates_from.parent().hide();
                    $('.sale_schedule').parent().hide();
                }
            }

            //Set Product Type if they are hidden
            if (general_settings_param.product_type_hidden && general_settings_param.product_type_hidden[0] &&
                general_settings_param.product_type_hidden[0] === 'hidden') {
                hide_general_proudct_type = true;
                //Set the prodcut type
                if (general_settings_param.product_type_default) {
                    product_type_default = general_settings_param.product_type_default;

                    select_product_type.val(general_settings_param.product_type_default).change();
                    select_product_type.hide();
                }
                //Set if is virtual or downloadable
                if (general_settings_param.product_type_options) {
                    var virtual_val = (general_settings_param.product_type_options['_virtual'] !== undefined);
                    var downloadable_val = (general_settings_param.product_type_options['_downloadable'] !== undefined);

                    if (virtual_val) {

                        virtual.attr('checked', true).change();
                        virtual.prop('checked', true);
                    }
                    if (downloadable_val) {

                        downloadable.attr('checked', true).change();
                        downloadable.prop('checked', true);
                    }


                }
            }
            else {
                hide_general_proudct_type = false;
                $('span.type_box.hidden').removeClass('hidden');
            }

            var general_tab_hidden_fields = new Array();
            switch(product_type_default){

                case 'simple':
                    general_tab_hidden_fields.push( hide_general_proudct_type);
                    general_tab_hidden_fields.push(hide_general_tax_field);
                    general_tab_hidden_fields.push( hide_general_regular_price);
                    general_tab_hidden_fields.push( hide_general_sales_price);
                    general_tab_hidden_fields.push( hide_general_price_date);
                    break;
                case 'booking':
                    general_tab_hidden_fields.push( hide_general_proudct_type);
                    general_tab_hidden_fields.push( hide_general_tax_field);
                    break;
                default:
                     break;


            }


            var general_tab_hidden = Hooks.apply_filters( 'booking_general_tab_filter', general_tab_hidden_fields,product_type_default);

            var hidde_general_tab = true;
            if (general_tab_hidden.length >0) {

               $.each(general_tab_hidden,function(key,value){

                   if(value !==false){
                       hidde_general_tab = false;
                       return;
                   }
               });

               if(hidde_general_tab){
                   $('.general_options').hide();
                   $("#general_product_data").hide();
                   tabs_hided.push('general_tab');
               }
            }


        }


        //SKU
        if (general_settings_param.product_sku) {
            var sku_option = general_settings_param.product_sku;
            if (sku_option === "hidden" || sku_option === "none") {
                sku.parent().hide();
            }
        }
        //endregion

        //region Inventory tab
        //Manage stock
        if (general_settings_param.product_manage_stock && general_settings_param.product_manage_stock[0] && general_settings_param.product_manage_stock[0] !== undefined) {
            var hide_parent_1, hide_parent_2, inev_hide_parent_1, inev_hide_parent_2, inev_hide_parent_3 = false,
                manage_stock_opt = general_settings_param.product_manage_stock[0];
            if (manage_stock_opt === "manage") {
                manage_stock.attr('checked', true).change();
                //Stock Quantity
                if (general_settings_param.product_manage_stock_qty_options && general_settings_param.product_manage_stock_qty_options[0]) {
                    var stock_qty_opt = general_settings_param.product_manage_stock_qty_options[0];
                    if (stock_qty_opt === 'default') { //Hide if have default value
                        stock.parent().hide();
                        if (general_settings_param.product_manage_stock_qty) {
                            stock.val(general_settings_param.product_manage_stock_qty);
                        }
                        hide_parent_1 = true;
                    }
                }
                //Allow backorders
                if (general_settings_param.product_allow_backorders_options && general_settings_param.product_allow_backorders_options[0]) {
                    var backorder_opt = general_settings_param.product_allow_backorders_options[0];
                    if (backorder_opt === 'hidden') { //Hide if have default value
                        backorders.parent().hide();
                        if (general_settings_param.product_allow_backorders) {
                            backorders.find("option:selected").removeAttr('selected');
                            backorders.find("option[value='" + general_settings_param.product_allow_backorders + "']").attr('selected', 'selected');
                        }
                        hide_parent_2 = true;
                    }
                }
                if (manage_stock_opt === "manage" && hide_parent_1 === true && hide_parent_2 === true) {
                    manage_stock.parent().hide();
                    inev_hide_parent_1 = true;
                }
            }
        }
        //Sold individually
        if (general_settings_param.product_sold_individually_options && general_settings_param.product_sold_individually_options[0] && general_settings_param.product_sold_individually_options !== undefined) {
            var sold_individually_opt = general_settings_param.product_sold_individually_options[0];
            if (sold_individually_opt === "hidden") {
                sold_individually.parent().hide();
                if (general_settings_param.product_sold_individually && general_settings_param.product_sold_individually === 'yes') {
                    sold_individually.attr('checked', true).change();
                }
                inev_hide_parent_2 = true;
            }
        }
        //Stock Status
        if (general_settings_param.product_stock_status_options && general_settings_param.product_stock_status_options[0] && general_settings_param.product_stock_status_options !== undefined) {
            var in_stock_opt = general_settings_param.product_stock_status_options[0];
            if (in_stock_opt === "hidden") {
                stock_status.parent().hide();
                if (general_settings_param.product_stock_status) {
                    stock_status.val(general_settings_param.product_stock_status).change();
                }
                inev_hide_parent_3 = true;
            }
        }

        if (inev_hide_parent_1 === true && inev_hide_parent_2 === true && inev_hide_parent_3 === true) {
            $('.inventory_options').removeClass('show_if_simple show_if_variable show_if_grouped show_if_external').hide();
            $('#inventory_product_data').hide();
            tabs_hided.push('inventory_tab');
        }
        //endregion

        //region Inventory tab
        // PRODUCT SHIPPING
        if (general_settings_param.product_shipping_hidden && general_settings_param.product_shipping_hidden[0] && general_settings_param.product_shipping_hidden !== undefined) {
            var shipp_hide_parent_1, shipp_hide_parent_2, shipp_hide_parent_3, shipp_hide_parent_4, shipp_hide_parent_5,
                shipp_hide_parent_6 = false,
                shipping_opt = general_settings_param.product_shipping_hidden[0];
            if (shipping_opt === "hidden") {
                //Hide the entire tab
                $('.shipping_options').addClass('hide_if_simple hide_if_auction hide_if_variable').hide();
                $('#shipping_product_data').hide();
                tabs_hided.push('shipping_tab');

                if (general_settings_param.product_shipping_hidden_weight) {
                    weight.val(general_settings_param.product_shipping_hidden_weight).change();
                    weight.parent().hide();
                }
                else {
                    weight.val('');
                }

                if (general_settings_param.product_shipping_hidden_dimension_length) {
                    length.val(general_settings_param.product_shipping_hidden_dimension_length).change();
                    length.hide();
                }
                else {
                    length.val('');
                }

                if (general_settings_param.product_shipping_hidden_dimension_width) {
                    var dimension_width = general_settings_param.product_shipping_hidden_dimension_width;
                    width.val(general_settings_param.product_shipping_hidden_dimension_width).change();
                    width.hide();
                }
                else {
                    width.val('');
                }

                if (general_settings_param.product_shipping_hidden_dimension_height) {
                    height.val(general_settings_param.product_shipping_hidden_dimension_height).change();
                    height.hide();
                }
                else {
                    height.val('');
                }

                if (general_settings_param.product_shipping_hidden_shipping_class) {
                    shipping_class.val(general_settings_param.product_shipping_hidden_shipping_class).change();
                    shipping_class.parent().parent().hide();
                }
            }
        }
        //endregion

        //region Linked Products
        //Up Sell
        var linked_parent_hide_1 = false, linked_parent_hide_2 = false, linked_parent_hide_3 = false;
        if (general_settings_param.product_up_sales && general_settings_param.product_up_sales[0] && general_settings_param.product_up_sales !== undefined) {
            var up_sales_opt = general_settings_param.product_up_sales[0];
            if (up_sales_opt === "hidden") {
                $("#upsell_ids").parent().remove();
                linked_parent_hide_1 = true;
            }
        }
        //Cross Sales
        if (general_settings_param.product_cross_sales && general_settings_param.product_cross_sales[0] && general_settings_param.product_cross_sales !== undefined) {
            var crosssell_opt = general_settings_param.product_cross_sales[0];
            if (crosssell_opt === "hidden") {
                $("#crosssell_ids").parent().remove();
                linked_parent_hide_2 = true;
            }
        }
        //Grouped
        if (general_settings_param.product_grouping && general_settings_param.product_grouping[0] && general_settings_param.product_grouping !== undefined) {
            var grouped_opt = general_settings_param.product_grouping[0];
            if (grouped_opt === "hidden") {
                $("#grouped_products").parent().remove();
                linked_parent_hide_3 = true;
            }
        }

        if (linked_parent_hide_1 === true && linked_parent_hide_2 === true && linked_parent_hide_3 === true) {
            $('.linked_product_options').hide();
            $('#linked_product_data').hide();
            tabs_hided.push('linked_product_tab');
        }
        //endregion

        //region Attributes
        if (general_settings_param.attributes_hide_tab && general_settings_param.attributes_hide_tab[0]
            && general_settings_param.attributes_hide_tab[0] === 'hidden') {
            attribute_container.hide();
            attribute_tab.hide();
            tabs_hided.push('attribute_tab');
        }
        //endregion

        //region Variations
        if (general_settings_param.variations_hide_tab && general_settings_param.variations_hide_tab[0]
            && general_settings_param.variations_hide_tab[0] === 'hidden') {
            variations_container.hide();
            variations_tab.removeClass('show_if_variable').hide();
            tabs_hided.push('variations_tab');
        }
        //endregion

        //region Advance
        var hide_advance_parent1 = false, hide_advance_parent2 = false, hide_advance_parent3 = false;
        if (general_settings_param.hide_purchase_notes && general_settings_param.hide_purchase_notes[0]
            && general_settings_param.hide_purchase_notes[0] === 'hidden') {
            purchase_note.parent().hide();
            hide_advance_parent1 = true;
            if (general_settings_param.purchase_notes && general_settings_param.purchase_notes !== "") {
                purchase_note.val(general_settings_param.purchase_notes).change();
            }
        }
        if (general_settings_param.hide_menu_order && general_settings_param.hide_menu_order[0]
            && general_settings_param.hide_menu_order[0] === 'hidden') {
            menu_order_input.parent().hide();
            hide_advance_parent2 = true;
            if (general_settings_param.menu_order && general_settings_param.menu_order !== "") {
                menu_order_input.val(general_settings_param.menu_order).change();
            }
        }
        if (general_settings_param.hide_enable_review_orders && general_settings_param.hide_enable_review_orders[0]
            && general_settings_param.hide_enable_review_orders[0] === 'hidden') {
            reviews_allowed.parent().hide();
            hide_advance_parent3 = true;
            if (general_settings_param.enable_review_orders === 'yes') {
                reviews_allowed.attr('checked', true).change();
            }
        }

        if (hide_advance_parent1 === true && hide_advance_parent2 === true && hide_advance_parent3 === true) {
            advanced_tab.hide();
            advanced_container.hide();
            tabs_hided.push('advanced_tab');
        }

        //endregion


        // Save attributes and update variations.
        $('.save_attributes').on('click', function () {

            $('#woocommerce-product-data').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var data = {
                post_id: woocommerce_admin_meta_boxes.post_id,
                product_type: $('#product-type').val(),
                data: $('.product_attributes').find('input, select, textarea').serialize(),
                action: 'woocommerce_save_attributes',
                security: woocommerce_admin_meta_boxes.save_attributes_nonce
            };

            $.post(woocommerce_admin_meta_boxes.ajax_url, data, function () {
                // Reload variations panel.
                var this_page = window.location.toString();
                this_page = this_page + '?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&';
                // Load variations panel.
                $('#variable_product_options').load(this_page + ' #variable_product_options_inner', function () {
                    $('#variable_product_options').trigger('reload');
                });
            });

        });

        $('form').trigger("reset");
        if (general_settings_param.debug) console.log(tabs_hided);
        main_container.find('.woo_general_loader').remove();
    });
});
