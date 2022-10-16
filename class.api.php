<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EDW_API' ) ) {
	class EDW_API {
		public function __construct() {
			add_action( 'wp_ajax_nopriv_edw_get_estimate_dates', [ $this, 'edw_get_estimate_dates' ] );
			add_action( 'wp_ajax_edw_get_estimate_dates',        [ $this, 'edw_get_estimate_dates' ] );
		}
		
		public function edw_get_estimate_dates() {
			global $EDWCore;
			
			$product = sanitize_text_field( $_POST['product'] );
			
			/*
			if ( $_POST['type'] == 'variation' ) {
				$variation = wc_get_product( $variation_id );
				$product   = $variation->get_parent_id();
			}
			*/
			
			$string = $EDWCore->edw_show_message( $product );
			
			if ( ! $string ) {
				$res = [];
			} else {
				$res = [ 'html' => $string ];
			}
			
			wp_send_json( $res );
			wp_die();
		}
	}
	
	$EDW_API = new EDW_API();
}
