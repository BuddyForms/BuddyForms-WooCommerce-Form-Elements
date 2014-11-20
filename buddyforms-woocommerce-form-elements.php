<?php
/*
 Plugin Name: BuddyForms WooCommerce Form Elements
 Plugin URI: http://buddyforms.com
 Description: This Plugin adds a new section to the BuddyForms Form Builder with all WooCommerce fields to create Product creation forms for the frontend
 Version: 1.0
 Author: Sven Lehnert
 Author URI: http://themekraft.com/members/svenl77/
 License: GPLv2 or later
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */



function buddyforms_woocommerce_admin_settings_sidebar_metabox($form, $selected_form_slug){

    $form->addElement(new Element_HTML('
		<div class="accordion-group postbox">
			<div class="accordion-heading"><p class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_'.$selected_form_slug.'" href="#accordion_'.$selected_form_slug.'_woocommerce_fields">WooCommerce Fields</p></div>
		    <div id="accordion_'.$selected_form_slug.'_woocommerce_fields" class="accordion-body collapse">
				<div class="accordion-inner">'));

                    $form->addElement(new Element_HTML('<p><b>Product Data</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Product-Type/'.$selected_form_slug.'/unique" class="action">Product Type</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Virtual/'.$selected_form_slug.'" class="action">Virtual</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Downloadable/'.$selected_form_slug.'" class="action">Downloadable</a></p>'));

                    $form->addElement(new Element_HTML('<p><b>General</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="SKU/'.$selected_form_slug.'" class="action">SKU</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Regular-Price/'.$selected_form_slug.'" class="action">Regular Price</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Sale-Price/'.$selected_form_slug.'" class="action">Sale Price</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Sale-Price-Dates/'.$selected_form_slug.'" class="action">Sale Price Dates</a></p>'));

                    $form->addElement(new Element_HTML('<p><b>Inventory</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Manage-stock/'.$selected_form_slug.'" class="action">Manage stock</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Stock-status/'.$selected_form_slug.'" class="action">Stock status</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Sold-Individually/'.$selected_form_slug.'" class="action">Sold Individually</a></p>'));

                    $form->addElement(new Element_HTML('<p><b>Shipping</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Shipping/'.$selected_form_slug.'" class="action">Shipping</a></p>'));
                    $form->addElement(new Element_HTML('<p><b>Linked Products</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Up-Sells/'.$selected_form_slug.'" class="action">Up-Sells</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Cross-Sells/'.$selected_form_slug.'" class="action">Cross-Sells</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Grouping/'.$selected_form_slug.'" class="action">Grouping</a></p>'));
                    $form->addElement(new Element_HTML('<p><b>Attributes</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Taxonomy/'.$selected_form_slug.'" class="action">Attributes Taxonomy</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Attribute/'.$selected_form_slug.'" class="action">Attribute</a></p>'));
                    $form->addElement(new Element_HTML('<p><b>Advanced</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Purchase-Note/'.$selected_form_slug.'" class="action">Purchase Note</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Menu-order/'.$selected_form_slug.'" class="action">Menu order</a></p>'));
                    $form->addElement(new Element_HTML('<p><b>Product Content</b></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Product-Short-Description/'.$selected_form_slug.'" class="action">Product Short Description</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Product-Gallery/'.$selected_form_slug.'" class="action">Product Gallery</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Product Categories/'.$selected_form_slug.'" class="action">Product Categories</a></p>'));
                        $form->addElement(new Element_HTML('<p><a href="Product Tags/'.$selected_form_slug.'" class="action">Product Tags</a></p>'));

                    $form->addElement(new Element_HTML('
				</div>
			</div>
		</div>'));

    return $form;
}
add_filter('buddyforms_admin_settings_sidebar_metabox','buddyforms_woocommerce_admin_settings_sidebar_metabox',1,2);


function buddyforms_woocommerce_create_new_form_builder_form_element($form_fields, $form_slug, $field_type, $field_id){

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
                'orderby'           => 'SLUG',
                'order'             => $taxonomy_order,
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
            $form_fields['right']['html']		= new Element_HTML('<p><b>The Virtual formelement has no options if its added to the form the Virtual Checkbox will be checked </b></p>');
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_virtual');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_virtual');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Downloadable':

            unset($form_fields);
            $form_fields['right']['html']		= new Element_HTML('<p><b>The Virtual formelement has no options if its added to the form the Virtual Checkbox will be checked </b></p>');
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
        case 'Manage-stock':

            unset($form_fields);
            $form_fields['right']['html']		= new Element_HTML('<p><b>The Virtual formelement has no options if its added to the form the Virtual Checkbox will be checked </b></p>');
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", '_manage_stock');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_manage_stock');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Stock-status':

            unset($form_fields);
            $form_fields['right']['html']		= new Element_HTML('<p><b>The Virtual formelement has no options if its added to the form the Virtual Checkbox will be checked </b></p>');
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Stock Status');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_stock_status');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;

    }


    return $form_fields;
}
add_filter('buddyforms_form_element_add_field','buddyforms_woocommerce_create_new_form_builder_form_element',1,5);


function buddyforms_woocommerce_create_frontend_form_element($form, $form_args){

    extract($form_args);

    $buddyforms_options = get_option('buddyforms_options');

    switch ($customfield['type']) {

        case 'Product-Type':

            $product_type_default = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default']))
                $product_type_default = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default'];

            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden'])){
                $form->addElement( new Element_Hidden($customfield['slug'], $product_type_default));
            } else {
                $args = array(
                    'hide_empty'        => 0,
                    'id'                => $customfield['slug'],
                    'child_of'          => 0,
                    'echo'              => FALSE,
                    'selected'          => false,
                    'hierarchical'      => 1,
                    'name'              => $customfield['slug'] . '[]',
                    'class'             => 'postform chosen',
                    'depth'             => 0,
                    'tab_index'         => 0,
                    'taxonomy'          => 'product_type',
                    'hide_if_empty'     => FALSE,
                );

                $dropdown = wp_dropdown_categories($args);

                $the_post_terms = get_the_terms( $post_id, $customfield['slug'] );

                if (is_array($the_post_terms)) {
                    foreach ($the_post_terms as $key => $post_term) {
                        $dropdown = str_replace(' value="' . $post_term->term_id . '"', ' value="' . $post_term->term_id . '" selected="selected"', $dropdown);
                    }
                    $dropdown = str_replace(' value="' . $customfield_val . '"', ' value="' . $customfield_val . '" selected="selected"', $dropdown);
                } else {
                    $dropdown = str_replace(' value="' . $product_type_default . '"', ' value="' . $product_type_default . '" selected="selected"', $dropdown);
                }


                $required = '';
                if(isset($customfield['required']) && is_array( $customfield['required'] )){
                    $required = '<span class="required">* </span>';
                }
                $dropdown = '<div class="bf_field_group">
                            <label for="editpost-element-' . $field_id . '">
                                '.$required.$customfield['name'] . ':
                            </label>
                            <div class="bf_inputs">' . $dropdown . ' </div>
                            <span class="help-inline">' . $customfield['description'] . '</span>
                        </div>';

                $form->addElement( new Element_HTML($dropdown));

            }

            break;
        case 'Virtual':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));
            break;
        case 'Downloadable':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));
            break;
        case 'SKU':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']);
            $form->addElement( new Element_Textbox($customfield['name'], $customfield['slug'], $element_attr));
            break;
        case 'Regular-Price':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']);
            $form->addElement( new Element_Number($customfield['name'], $customfield['slug'], $element_attr));
            break;
        case 'Sale-Price':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']);
            $form->addElement( new Element_Number($customfield['name'], $customfield['slug'], $element_attr));
            break;
        case 'Manage-stock':
            $form->addElement( new Element_Hidden($customfield['slug'], 'yes'));

            $customfield_val = get_post_meta($post_id, '_stock', true);
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']);
            $form->addElement( new Element_Number('Stock Qty', '_stock', $element_attr));

            $customfield_val = get_post_meta($post_id, '_backorders', true);
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']);
            $form->addElement( new Element_Select('Allow Backorders?', '_backorders', array('no' => 'Do not allow', 'notify' => 'Allow, but notify customer', 'yes' => 'Allow'), $element_attr));

            break;
        case 'Stock-status':
            $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']);
            $form->addElement( new Element_Select($customfield['name'], $customfield['slug'], array('instock' => 'In stock', 'outofstock' => 'Out of stock'), $element_attr));
            break;

    }

    return $form;

}
add_filter('buddyforms_create_edit_form_display_element','buddyforms_woocommerce_create_frontend_form_element',1,2);


add_action('buddyforms_update_post_meta', 'buddyforms_woocommerce__updtae_post_meta', 99, 2);
function buddyforms_woocommerce__updtae_post_meta($customfield, $post_id){

    if( $customfield['type'] == 'Product-Type' ){

        if(isset($customfield['product_type_hidden'])) {
            $slug = Array();
            $term = get_term_by('id', $customfield['product_type_default'], $customfield['slug']);
            $slug[] = $term->slug;
            wp_set_post_terms($post_id, $slug, $customfield['slug'], false);
        } else {
            $taxonomy = get_taxonomy($customfield['slug']);

            if (isset($taxonomy->hierarchical) && $taxonomy->hierarchical == true) {

                if (isset($_POST[$customfield['slug']]))
                    $tax_item = $_POST[$customfield['slug']];

                if ($tax_item[0] == -1)
                    $tax_item[0] = $customfield['product_type_default'];

                wp_set_post_terms($post_id, $tax_item, 'product_type', false);
            } else {

                $slug = Array();

                if (isset($_POST[$customfield['slug']])) {
                    $postCategories = $_POST[$customfield['slug']];

                    foreach ($postCategories as $postCategory) {
                        $term = get_term_by('id', $postCategory, $customfield['slug']);
                        $slug[] = $term->slug;
                    }
                }

                wp_set_post_terms($post_id, $slug, $customfield['slug'], false);

            }
        }
    }
    if( $customfield['type'] == 'Manage-stock' ){
        update_post_meta($post_id, '_stock', $_POST['_stock'] );
        update_post_meta($post_id, '_backorders', $_POST['_backorders'] );
    }


}

?>