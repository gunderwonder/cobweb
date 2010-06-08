<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class ChoiceField extends FormField {
	
	protected $choices = array();
	
	public function __construct(array $choices, array $properties = array()) {
		$this->choices = $choices;
		parent::__construct($properties);
	}
	
	public function choices() {
		return $this->choices;
	}
	
	public function clean($value) {
		if ($this->isRequired() && !in_array($value, array_keys($this->choices)))
			throw new FormValidationException($this->error_messages['invalid_choice']);
			
		return $value;
	}
	
	protected function defaultProperties() {
		return array(
			'error_messages' => array('invalid_choice' => __('Invalid choice')),
			'widget' => new SelectInput()
		);
	}
}

class BooleanField extends FormField {
	
	public function clean($value) {
		if ($this->isRequired() && !$value)
			throw new FormValidationException($this->error_messages['required']);
		return (bool)$value;
	}
	
	protected function defaultProperties() {
		return array(
			'widget' => new CheckboxInput()
		);
	}
}

