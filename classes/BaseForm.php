<?php

abstract class BaseForm {
	/**
	 * @var int|null Settings are either set on a product or globally
	 */
	protected $productId;
	
	/**
	 * @var string The name of the template to use as a basis
	 */
	protected $template;
	
	/**
	 * @var bool Should this form always override the global settings?
	 *           If yes, the checkbox won't be displayed.
	 */
	protected $forcedOverride;
	
	/**
	 * @var string[] A list of days that are "disabled" (no shipment done)
	 */
	protected $disabledDays;
	
	/**
	 * @var int The display mode of the string (Estimated, Guaranteed, Custom)
	 */
	protected $displayMode;
	
	/**
	 * @var bool If true, the default value in the template is ignored and
	 *           replaced by an empty string.
	 */
	protected $emptyDefault;
	
	/**
	 * @var bool If true, global settings in the template must be shown
	 */
	protected $isGlobal;
	
	/**
	 * This function is made to be overridden by form classes to display custom
	 * HTML tags before the template if it's used by many forms
	 *
	 * @return void
	 */
	protected function displayBeforeForm() { }
	
	/**
	 * This function is made to be overridden by form classes to display custom
	 * HTML tags after the template if it's used by many forms
	 *
	 * @return void
	 */
	protected function displayAfterForm() { }
	
	/**
	 * This function is made to be overridden by form classes for non-default cases
	 *
	 * @param $key     string The key setting to retrieve
	 * @param $default mixed  The default value if the result is '' or false
	 *
	 * @return mixed
	 */
	public function retrieveProperty( string $key, $default = '' ) {
		if ( $this->isGlobal ) {
			$val = get_option( $key, $default );
		} else {
			$val = get_post_meta( $this->productId, $key, true );
			
			if ( ! $val ) {
				$val = $this->emptyDefault ? '' : $default;
			}
		}
		
		return $val;
	}
	
	/**
	 * The usual "get form" method of the classes
	 *
	 * @return void
	 */
	public function displayForm() {
		$this->displayBeforeForm();
		include __DIR__ . '/../views/templates/' . $this->template . '.php';
		$this->displayAfterForm();
	}
}
