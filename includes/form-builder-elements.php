<?php


function buddyforms_woocommerce_admin_settings_sidebar_metabox($form, $selected_form_slug){

    $form->addElement(new Element_HTML('
		<div class="accordion-group postbox">
			<div class="accordion-heading"><p class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_'.$selected_form_slug.'" href="#accordion_'.$selected_form_slug.'_woocommerce_fields">WooCommerce Fields</p></div>
		    <div id="accordion_'.$selected_form_slug.'_woocommerce_fields" class="accordion-body collapse">
				<div class="accordion-inner">'));

    $form->addElement(new Element_HTML('<p><b>Product General Data</b></p>'));

        $form->addElement(new Element_HTML('<p><a href="General/'.$selected_form_slug.'/unique" class="action">General</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Inventory</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Inventory/'.$selected_form_slug.'/unique" class="action">Inventory</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Shipping</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Shipping/'.$selected_form_slug.'" class="action">Shipping</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Linked Products</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Linked-Products/'.$selected_form_slug.'/unique" class="action">Linked Products</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Attributes</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Attributes/'.$selected_form_slug.'/unique" class="action">Attributes</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Product Gallery</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Product-Gallery/'.$selected_form_slug.'/unique" class="action">Product Gallery</a></p>'));

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

        case 'General':

            unset($form_fields);

            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'General');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_general');

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
        case 'Inventory':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Inventory');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_inventory');

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
        case 'Linked-Products':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Linked Products');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_linked_products');

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
