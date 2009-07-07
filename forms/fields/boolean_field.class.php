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
class BooleanField extends FormField {
	
	public function clean($value) {
		$cleaned = parent::clean($value);
		
		return !empty($value);
	}
	
	
	public function defaultWidget() {
		return new CheckboxInput();
	}
}