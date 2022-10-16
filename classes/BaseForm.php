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
	 * This function is made to be overwritten by form classes to display custom
	 * HTML tags before the template if it's used by many forms
	 *
	 * @return void
	 */
	protected function displayBeforeForm() { }
	
	/**
	 * This function is made to be overwritten by form classes to display custom
	 * HTML tags after the template if it's used by many forms
	 *
	 * @return void
	 */
	protected function displayAfterForm() { }
	
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
