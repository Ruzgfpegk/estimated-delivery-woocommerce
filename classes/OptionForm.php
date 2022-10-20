<?php
declare( strict_types = 1 );

/**
 * Class OptionForm
 *
 * The global settings in WooCommerce/Estimated Delivery
 */
class OptionForm extends BaseForm {
	public function __construct() {
		$this->isGlobal       = true;
		$this->emptyDefault   = false;
		$this->forcedOverride = false;
		$this->template       = 'default';
		$this->productId      = null;
		$this->disabledDays   = get_option( '_edw_disabled_days', [] );
		$this->displayMode    = get_option( '_edw_mode', '1' );
	}
}
