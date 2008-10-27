<?php

class IntegerField extends FormField {
	
	
	public function errorMessages() {
		return array(
			'invalid' => "'%s' is not a valid integer"
		);
	}
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		if (utf8_strlen($cleaned) === 0)
			$this->error('invalid');
		
		if ($cleaned[0] == '-')
			if (ctype_digit(utf8_substr($cleaned, 1)))
				return intval($cleaned);
		if (ctype_digit($cleaned))
			return intval($cleaned);
			
		$this->error('invalid');
	}
}