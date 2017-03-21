<?php


	function _bf_woo_elem( $str ) {
		return __( $str, 'bf_woo_elem_locale' );
	}

	function  _e_bf_woo_elem( $str ) {
		_e( $str, 'bf_woo_elem_locale' );
	}

	function _esc_html_e_bf_woo_elem( $str ) {
		echo esc_html( _bf_woo_elem( $str ) );
	}