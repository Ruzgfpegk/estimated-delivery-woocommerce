<?php
/**
 * Plugin Name: Estimated Delivery for WooCommerce
 * Description: Show estimated / guaranteed delivery, simple and easy
 * Author: Daniel Riera & Ruzgfpegk
 * Author URI: https://danielriera.net
 * Version: 1.2.7-r6
 * Text Domain: estimated-delivery-for-woocommerce
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 6.8.2
 * Required WP: 5.0
 * Tested WP: 6.0.1
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const EDW_PATH    = __DIR__ . '/';
const EDW_VERSION = '1.2.7-r6';

spl_autoload_register( static function( $class ) {
	include EDW_PATH . 'classes/' . $class . '.php';
} );


define( 'EDW_POSITION_SHOW', get_option( '_edw_position', 'woocommerce_after_add_to_cart_button' ) );
define( 'EDW_USE_JS',        get_option( '_edw_cache',    '0' ) );

$EDWCore = new EDWCore();
$EDW_API = new EDW_API();
