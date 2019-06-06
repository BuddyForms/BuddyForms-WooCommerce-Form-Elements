<?php
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

if ( class_exists( 'Element_Price' ) ) {
	class bf_woo_elem_regular_price extends Element_Price {

		public static function definition() {
			return array(
				'regular_price' => array(
					'label'  => __( 'Regular Price', 'buddyforms' ),
					'unique' => 'unique',
				)
			);
		}

		public function render() {
			if ( ! empty( $this->field_options ) ) {
				$this->_attributes["class"] .= ' bf_woo_price bf_woo_regular_price';
			}
			parent::render();
		}

		public static function builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyform ) {
			$form_fields = parent::builder_element_options($form_fields, $form_slug, $field_type, $field_id, $buddyform);
			unset( $form_fields['advanced']['slug'] );
			unset( $form_fields['advanced']['metabox_enabled'] );
			$form_fields['hidden']['slug'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", 'regular_price' );
			$form_fields['hidden']['type'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );

			return $form_fields;
		}
	}
}