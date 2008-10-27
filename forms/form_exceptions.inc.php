<?php
/**
 * @version $Id$
 */

class FormException extends Exception { }

class FormValidationException extends FormException {
	
	private $errors;
	
	public function __construct(array $errors) {
		$this->errors = $errors;
	}
	
	public function errors() {
		return $this->errors;
	}
}