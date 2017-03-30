<?php

class bf_woo_elem_product_downloadable {

	public static function bf_wc_downloadable( $thepostid, $customfield ) {
		global $post;
		$post = get_post( $thepostid );
		include_once( BF_WOO_ELEM_VIEW_PATH . 'form-elements/bf_woo_elem_product_downloadable_view.php' );
	}

}