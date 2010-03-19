<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

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