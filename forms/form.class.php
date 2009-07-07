<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once dirname(__FILE__) . '/../vendor/utf8/utf8.php';

/**
 * TODO: add global error messages/translations
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Forms
 * @version    $Revision$
 */
abstract class Form implements IteratorAggregate {
	
	private $fields = array();
	private $errors;
	private $data;
	
	private $clean_data = array();

	
	public function __construct($data = NULL) {
		$this->clean_data = array();
		
		$this->configure();
		$this->bind($data);
		
		$this->inspect();
	}
	
	public function bind($data = NULL) {
		$this->data = $data;
		if ($this->isBound())
			$this->validate($data);
	}
	
	abstract protected function configure() ;
	
	private function inspect() {
	
	}
	
	public function isBound() {
		return !is_null($this->data);
	}
	
	public function isValid() {
		return is_null($this->errors());
	}
	
	public function errors() {
		if (!$this->isBound())
			return false;
		
		return $this->errors;
	}
	
	public function addError($field_id, $error_message) {
		if (!isset($this->errors[$field_id]))
			$this->errors[$field_id] = array();
			
		$this->errors[$field_id][] = $error_message;
			
	}
	
	public function validate() {
		
		$this->clean_data = array();
		
		foreach ($this->fields as $key => $field) {
				
			try {
				$this->clean_data[$key] = $field->clean(
					$field->widget()->value($this->data, FormField::HTMLize($key), NULL));
			
			} catch (FormValidationException $e) {
				$this->errors[FormField::HTMLize($key)] = $e->errors();
			}

		}
	}
	
	protected function __set($key, $value) {
		if ($value instanceof FormField) {
			$this->fields[$key] = $value;
			$value->setForm($this);
			$value->setName($key);
			return;
		}
		// $this->$key = $value;
			
	}
	
	public function __get($key) {
		$getter = 'get' . ucfirst($key);

		if (method_exists($this, $getter))
			return $this->$getter();
		
		$htmlized_key = FormField::HTMLize($key);
		
		if (isset($this->clean_data[$key]))
			return $this->clean_data[$key];
		if (isset($this->clean_data[$htmlized_key]))
			return $this->clean_data[$key];
		
		if (isset($this->data[$key]))
			return $this->data[$key];
		if (isset($this->data[$htmlized_key]))
			return $this->data[$htmlized_key];
		
		if (!isset($this->fields[$key]))
			throw new FormException("Field '{$key}' is not defined");
			
		throw new FormException("Unknown field '{$key}'");
	}
	
	public function assign($object) {
		if (!$this->isValid())
			throw new FormException('Cannot assign form values to object. The form is invalid');
		
		if (!is_object($object))
			throw new UnexpectedValueException('Can only assign form values to objects.');
			
		foreach ($this->fields as $clean_data => $field)
			$object->$key = $this->clean_data[$key];
	}
	
	public function data() {
		return $this->data;
	}
	
	public function field($key) {
		return $this->fields[$key];
	}
	
	public function cleanData() {
		return $this->clean_data;
	}
	
	public function getIterator() {
		return new ArrayIterator($this->fields);
	}
	
	public static function create(array $specification, $data = NULL) {
		return new ConcreteForm($specification, $data);
	}
	
	
	
	
}

