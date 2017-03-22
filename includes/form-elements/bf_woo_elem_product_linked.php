<?php


class bf_woo_elem_product_linked {
	public static function bf_wc_product_linked( $thepostid, $customfield ) {
		global $post;
		include_once( BF_WOO_ELEM_VIEW_PATH . 'form-elements/bf_woo_elem_product_linked_view.php' );
	}
}