<?php

class BooleanField extends FormField {
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		
		return empty($value);
	}
	
	
	public function defaultWidget() {
		return new CheckboxInput();
	}
}