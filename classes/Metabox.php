<?php
declare( strict_types = 1 );

/**
 * Class Metabox
 *
 * The Metabox is the standard way to have product settings in WooCommerce,
 * when editing a product.
 */
class Metabox extends BaseForm {
	public function __construct() {
		if( get_post_type() !== 'product' ) {
			throw new RuntimeException('Not called from a product page!');
		}
		
		global $post;
		
		$this->isGlobal       = false;
		$this->emptyDefault   = true;
		$this->forcedOverride = false;
		$this->template       = 'default';
		$this->productId      = $post->ID;
		$this->disabledDays   = get_post_meta( $this->productId, '_edw_disabled_days', true );
		$this->displayMode    = get_post_meta( $this->productId, '_edw_mode', true );
	}
}
