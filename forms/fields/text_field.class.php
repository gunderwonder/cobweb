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
class TextField extends FormField {
	
	public function clean($value) {
		$cleaned = parent::clean($value);

		if (($max_length = $this->specification->get('max_length', NULL)))
			if (utf8_strlen($cleaned) > $max_length)
				$this->error('max_length', array($this->label(), $max_length));
		
		if ($min_length = $this->specification->get('min_length', NULL))
			if (utf8_strlen($cleaned) > $max_length)
				$this->error('min_length', array($this->label(), $min_length));
		
		return (string)$cleaned;
	}
	
	public function errorMessages() {
		return array(
			'max_length' => "'%s' may not be longer than %d characters",
			'min_length' => "'%s' may not be shorter than %d characters"
		);
	}
}