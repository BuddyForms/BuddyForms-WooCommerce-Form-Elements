<?php

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
