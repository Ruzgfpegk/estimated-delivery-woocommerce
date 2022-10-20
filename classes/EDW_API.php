<?php
declare( strict_types = 1 );

class EDW_API {
	public function __construct() {
		add_action( 'wp_ajax_nopriv_edw_get_estimate_dates', [ $this, 'getEstimationDates' ] );
		add_action( 'wp_ajax_edw_get_estimate_dates',        [ $this, 'getEstimationDates' ] );
	}
	
	public function getEstimationDates() {
		global $EDWCore;
		
		$product = sanitize_text_field( $_POST['product'] );
		
		/*
		if ( $_POST['type'] == 'variation' ) {
			$variation = wc_get_product( $variation_id );
			$product   = $variation->get_parent_id();
		}
		*/
		
		$string = $EDWCore->showEstimationMessage( $product );
		
		if ( ! $string ) {
			$res = [];
		} else {
			$res = [ 'html' => $string ];
		}
		
		wp_send_json( $res );
		wp_die();
	}
}
