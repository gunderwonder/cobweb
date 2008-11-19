<?php

class EmailField extends FormField {
	
	const EMAIL_REGEX = '/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)'.
	                    '|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		
		if (!preg_match(self::EMAIL_REGEX, $value))
			// $this->error();
	}
}