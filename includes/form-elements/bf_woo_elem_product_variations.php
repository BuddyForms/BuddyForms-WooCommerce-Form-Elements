<?php


class bf_woo_elem_product_variations {
	public static function bf_wc_variations_custom( $thepostid, $customfield ) {
		global $variation_data, $post;


		$post = get_post( $thepostid );

		$variation_data = new WC_Product_Variation( $post );
		$variation_data->get_variation_attributes();

		$variation_data = (array) $variation_data;

		extract( $variation_data );
		include_once( BF_WOO_ELEM_VIEW_PATH . 'form-elements/bf_woo_elem_product_variations_view.php' );


	}
}