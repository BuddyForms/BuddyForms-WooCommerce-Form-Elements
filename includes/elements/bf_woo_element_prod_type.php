<?php
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */
if ( class_exists( 'Element_Select' ) ) {
    class bf_woo_elem_prod_type extends Element_Select
    {

        public static function definition()
        {
            return array(
                'product-type' => array(
                    'label' => __('Product Type', 'buddyforms'),
                    'unique' => 'unique',
                )
            );
        }
        public function render() {
            global $buddyforms;

            $this->_attributes["id"] = 'product-type';
            $this->_attributes["name"] = 'product-type';
            $form_slug = $this->options['data-form'];
            $field_id = $this->options['field_id'];
            $form_fields = $buddyforms[$form_slug]['form_fields'];
            $this->_attributes["value"] = isset($form_fields[$field_id]['product_type_default'])? $form_fields[$field_id]['product_type_default']: 'simple';
            $this->options = wc_get_product_types();
            parent::render();
        }
        public static function builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyform ) {
            //$form_fields = parent::builder_element_options($form_fields, $form_slug, $field_type, $field_id, $buddyform);
            unset( $form_fields['advanced']['slug'] );
            unset( $form_fields['advanced']['metabox_enabled'] );
            $form_fields['hidden']['slug'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", 'product-type' );
            $form_fields['hidden']['type'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );
            $product_type_default = apply_filters('default_product_type', 'simple');
            if (isset($buddyform['form_fields'][$field_id]['product_type_default'])) {
                $product_type_default = $buddyform['form_fields'][$field_id]['product_type_default'];
            }
            $product_type = apply_filters('default_product_type', 'simple');

            $product_type_selector = wc_get_product_types();

            $form_fields['general']['product_type_default'] = new Element_Select(
                '<b>' . __('Default Product Type: ', 'buddyforms') . '</b>',
                'buddyforms_options[form_fields][' . $field_id . '][product_type_default]',
                $product_type_selector,
                array(
                    'id' => 'product-type',
                    'class' =>  '',
                    'value' => $product_type_default,
                    'selected' => isset($product_type_default) ? $product_type_default : 'false',
                )
            );
            return $form_fields;
        }
    }
}