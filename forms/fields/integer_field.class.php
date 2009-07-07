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
class IntegerField extends FormField {
	
	
	public function errorMessages() {
		return array(
			'invalid' => "'%s' is not a valid integer"
		);
	}
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		if (empty($cleaned) === 0)
			$this->error('invalid');
		
		if ($cleaned[0] == '-' && ctype_digit(utf8_substr($cleaned, 1)))
			return intval($cleaned);
				
		if (ctype_digit($cleaned))
			return intval($cleaned);
			
		$this->error('invalid');
	}
}