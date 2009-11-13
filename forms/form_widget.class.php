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
abstract class FormWidget {
	
	protected $attributes = array();
	
	public function __construct($attributes = array()) {
		$this->attributes = $attributes;
	}
	
	public function extract($data, $field_name) {
		return $data->get($field_name, NULL);
	}
	
	abstract function render(FormField $field, $data, $attributes = array());
	
}