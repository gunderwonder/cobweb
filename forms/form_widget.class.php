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
	
	private $specification = array(
		'initial' => ''
	);
	
	abstract public function render($data = NULL);
	
	public function value(array $data, $key, $default) {
		if (!isset($data[$key]))
			return $default;
			
		
		return $data[$key];
	}
	
	public function initialData() {
		return $this->specification['initial'];
	}
	
	public function setField(FormField $field) {
		$this->field = $field;
		$this->specification = array_merge($this->specification, 
			                               $this->field->specification);
	}
	
}