<?php
declare( strict_types = 1 );

/**
 * Class DokanMP_Metabox
 *
 * The Dokan Marketplace Metabox is how Dokan Marketplace does things.
 * WARNING: This class is untested.
 */
class DokanMP_Metabox extends BaseForm {
	public function __construct() {
		global $post;
		
		$this->isGlobal       = false;
		$this->emptyDefault   = true;
		$this->forcedOverride = true;
		$this->template       = 'default';
		$this->productId      = $post->ID ?? 0;
		$this->disabledDays   = get_post_meta( $this->productId, '_edw_disabled_days', true );
		$this->displayMode    = get_post_meta( $this->productId, '_edw_mode', true );
	}
	
	protected function displayBeforeForm() {
		echo '<div class="row-padding">' . PHP_EOL;;
	}
	
	protected function displayAfterForm() {
		echo '</div>' . PHP_EOL;
	}
}
