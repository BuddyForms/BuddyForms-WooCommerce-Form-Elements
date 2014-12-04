<?php


function buddyforms_woocommerce_admin_settings_sidebar_metabox($form, $selected_form_slug){

    $form->addElement(new Element_HTML('
		<div class="accordion-group postbox">
			<div class="accordion-heading"><p class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_'.$selected_form_slug.'" href="#accordion_'.$selected_form_slug.'_woocommerce_fields">WooCommerce Fields</p></div>
		    <div id="accordion_'.$selected_form_slug.'_woocommerce_fields" class="accordion-body collapse">
				<div class="accordion-inner">'));

    $form->addElement(new Element_HTML('<p><b>Product Data</b></p>'));
    $form->addElement(new Element_HTML('<p><a href="Product-Type/'.$selected_form_slug.'/unique" class="action">Product Type</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Virtual/'.$selected_form_slug.'/unique" class="action">Virtual</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Downloadable/'.$selected_form_slug.'/unique" class="action">Downloadable</a></p>'));

    $form->addElement(new Element_HTML('<p><b>General</b></p>'));
    $form->addElement(new Element_HTML('<p><a href="SKU/'.$selected_form_slug.'/unique" class="action">SKU</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Regular-Price/'.$selected_form_slug.'/unique" class="action">Regular Price</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Sale-Price/'.$selected_form_slug.'/unique" class="action">Sale Price</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Sale-Price-Dates/'.$selected_form_slug.'/unique" class="action">Sale Price Dates</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Inventory</b></p>'));
    $form->addElement(new Element_HTML('<p><a href="Manage-stock/'.$selected_form_slug.'/unique" class="action">Manage stock</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Stock-status/'.$selected_form_slug.'/unique" class="action">Stock status</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Sold-Individually/'.$selected_form_slug.'/unique" class="action">Sold Individually</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Shipping</b></p>'));
    $form->addElement(new Element_HTML('<p><a href="Shipping/'.$selected_form_slug.'" class="action">Shipping</a></p>'));
//
//                    $form->addElement(new Element_HTML('<p><b>Linked Products</b></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Up-Sells/'.$selected_form_slug.'/unique" class="action">Up-Sells</a></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Cross-Sells/'.$selected_form_slug.'/unique" class="action">Cross-Sells</a></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Grouping/'.$selected_form_slug.'/unique" class="action">Grouping</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Attributes</b></p>'));
    $form->addElement(new Element_HTML('<p><a href="Attributes/'.$selected_form_slug.'/unique" class="action">Attributes</a></p>'));




//                    $form->addElement(new Element_HTML('<p><b>Advanced</b></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Purchase-Note/'.$selected_form_slug.'/unique" class="action">Purchase Note</a></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Menu-order/'.$selected_form_slug.'/unique" class="action">Menu order</a></p>'));
//
    $form->addElement(new Element_HTML('<p><b>Product Content</b></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Product-Short-Description/'.$selected_form_slug.'/unique" class="action">Product Short Description</a></p>'));
    $form->addElement(new Element_HTML('<p><a href="Product-Gallery/'.$selected_form_slug.'/unique" class="action">Product Gallery</a></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Product Categories/'.$selected_form_slug.'/unique" class="action">Product Categories</a></p>'));
//                        $form->addElement(new Element_HTML('<p><a href="Product Tags/'.$selected_form_slug.'/unique" class="action">Product Tags</a></p>'));

    $form->addElement(new Element_HTML('
				</div>
			</div>
		</div>'));

    return $form;
}
add_filter('buddyforms_admin_settings_sidebar_metabox','buddyforms_woocommerce_admin_settings_sidebar_metabox',1,2);


function buddyforms_woocommerce_create_new_form_builder_form_element($form_fields, $form_slug, $field_type, $field_id){
    global $field_position;
    $buddyforms_options = get_option('buddyforms_options');


    switch ($field_type) {

        case 'Product-Type':

            unset($form_fields);

            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'product_type');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", 'product_type');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            $wp_dropdown_categories_args = array(
                'hide_empty'        => 0,
                'child_of'          => 0,
                'echo'              => FALSE,
                'selected'          => false,
                'hierarchical'      => 1,
                'name'              => "buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_type_default]",
                'class'             => 'postform chosen',
                'depth'             => 0,
                'tab_index'         => 0,
                'taxonomy'          => 'product_type',
                'hide_if_empty'     => FALSE,
            );

            $dropdown = wp_dropdown_categories($wp_dropdown_categories_args);

            $product_type_default = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default']))
                $product_type_default = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default'];

            if($product_type_default)
                $dropdown = str_replace(' value="' . $product_type_default . '"', ' value="' . $product_type_default . '" selected="selected"', $dropdown);

            $dropdown = '<div class="bf_field_group">
                    <div class="buddyforms_field_label"><b>Product Type Default</b></div>
                    <div class="bf_inputs">' . $dropdown . ' </div>

                </div>';

            $form_fields['left']['product_type_default'] 		= new Element_HTML($dropdown);

            $product_type_hidden = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden']))
                $product_type_hidden = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden'];
            $form_fields['left']['product_type_hidden']		= new Element_Checkbox('' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_type_hidden]",array('hidden' => '<b>' .__('Make a hidden field', 'buddyforms') . '</b>'),array('value' => $product_type_hidden));


            break;
        case 'Virtual':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_virtual');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_virtual');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Downloadable':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_downloadable');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_downloadable');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'SKU':
            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'SKU');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_sku');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));
            break;
        case 'Regular-Price':
            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Regular Price');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_regular_price');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));
            break;
        case 'Sale-Price':
            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Sale Price');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_sale_price');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));
            break;
        case 'Sale-Price-Dates':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Sale Price Dates');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_sale_price_dates');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Manage-stock':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_manage_stock');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_manage_stock');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Stock-status':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Stock Status');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_stock_status');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Sold-Individually':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_sold_individually');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_sold_individually');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Shipping':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Shipping');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_shipping');

            $form_fields['right']['type']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));


            break;
        case 'Up-Sells':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_upsell_ids');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_upsell_ids');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Attributes':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Attributes');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_bf_wc_attributes');

            $form_fields['right']['type']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));


            $taxonomies = buddyforms_taxonomies($form_slug);
            $bf_wc_attributes_tax = Array();
            foreach($taxonomies as $key => $taxonomie){
                if(substr($taxonomie, 0, 3) == 'pa_')
                    $bf_wc_attributes_tax[$taxonomie] = $taxonomie;
            }

            $bf_wc_attributes_pa = false;
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['_bf_wc_attributes_pa']))
                $bf_wc_attributes_pa = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['_bf_wc_attributes_pa'];
            $form_fields['left']['_bf_wc_attributes_pa'] 		= new Element_Checkbox('<b>' . __('Attribute Taxonomies', 'buddyforms') . '</b><p><smal>Select the Attributes Taxonomies you want to include. This are the Attributes you have created under Product/Attributes</smal></p>', "buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][_bf_wc_attributes_pa]", $bf_wc_attributes_tax, array('value' => $bf_wc_attributes_pa));

            $attr_new_custom_field = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['attr_new_custom_field']))
                $attr_new_custom_field = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['attr_new_custom_field'];
            $form_fields['left']['attr_new_custom_field']	= new Element_Checkbox('<b>'.__('Custom Attribute', 'buddyforms').'</b> <p><smal>This is the same as the Custom Attributes in the Product edit Screen</smal></p>' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][attr_new_custom_field]",array('attr_new' => '<b>' .__('User can create new custom fields ', 'buddyforms') . '</b>'),array('value' => $attr_new_custom_field));

            break;
        case 'Product-Gallery':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Product Gallery');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", 'bf_product_gallery');

            $form_fields['right']['type']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));


            break;

    }


    return $form_fields;
}
add_filter('buddyforms_form_element_add_field','buddyforms_woocommerce_create_new_form_builder_form_element',1,5);
