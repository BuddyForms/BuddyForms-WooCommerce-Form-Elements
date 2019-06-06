<?php
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

if ( class_exists( 'Element_Textbox' ) ) {
	class bf_woo_elem_price extends Element_Textbox {

		/**
		 * bf_woo_elem_price constructor.
		 *
		 * @param $label
		 * @param $name
		 * @param $field_options
		 * @param array|null $properties
		 */
		public function __construct( $label, $name, $properties, array $field_options = null ) {

			parent::__construct( $label, $name, $properties, $field_options );
		}

		public function definition() {
			return array(
				'price' => array(
					'label'  => __( 'Price', 'buddyforms' ),
					'unique' => 'unique',
				)
			);
		}

		public function render() {
			if ( ! empty( $this->field_options ) ) {
				$this->_attributes["class"] .= ' bf_woo_price ';
			}
			wp_enqueue_script( 'jquery.priceformat', BF_WOO_ELEM_JS_PATH . 'jquery.priceformat.min.js', array( 'jquery' ), bf_woo_elem_manager::get_version(), false );
			wp_enqueue_script( 'bf_woo_price', BF_WOO_ELEM_JS_PATH . 'bf_woo_price.js', array( 'jquery', 'jquery.priceformat' ), bf_woo_elem_manager::get_version(), false );
			parent::render();
		}

		public function builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyform ) {
			unset( $form_fields['advanced']['slug'] );
			unset( $form_fields['advanced']['metabox_enabled'] );
			$form_fields['hidden']['slug'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", 'price' );
			$form_fields['hidden']['type'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );

			return $form_fields;
		}
	}
}