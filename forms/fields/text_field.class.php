<?php

class TextField extends FormField {
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		return (string)$cleaned;
	}
}