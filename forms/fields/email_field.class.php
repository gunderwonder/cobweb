<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class EmailField extends FormField {
	
	const EMAIL_REGEX = '/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		
		if (!preg_match(self::EMAIL_REGEX, $value))
			$this->error('invalid_email', array($this->label()));
	}
	
	public function errorMessages() {
		return array(
			'invalid_email' => "'%s' is not a valid email address",
		);
	}
}