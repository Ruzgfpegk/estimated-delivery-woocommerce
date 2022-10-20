<?php
declare( strict_types = 1 );

/**
 * Class WCMP_Metabox
 *
 * The WC Marketplace Metabox is how WC Marketplace does things.
 * WARNING: This class is untested.
 */
class WCMP_Metabox extends BaseForm {
	public function __construct() {
		global $product;
		
		$this->isGlobal       = false;
		$this->emptyDefault   = true;
		$this->forcedOverride = true;
		$this->template       = 'default';
		$this->productId      = $product->get_id();
		$this->disabledDays   = get_post_meta( $this->productId, '_edw_disabled_days', true );
		$this->displayMode    = get_post_meta( $this->productId, '_edw_mode', true );
	}
	
	protected function displayBeforeForm() {
		echo '<div role="tabpanel" class="tab-pane fade" id="edw_estimate_delivery"> <!-- just make sure tabpanel id should replace with your added tab target -->' . PHP_EOL;
		echo '<div class="row-padding">' . PHP_EOL;;
	}
	
	protected function displayAfterForm() {
		echo '</div>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
	}
}
