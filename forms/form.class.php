<?php
/**
 * @version $Id$
 */

require_once dirname(__FILE__) . '/../vendor/utf8/utf8.php';

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Forms
 * @version    $Revision$
 */
abstract class Form {
	
	private $fields = array();
	private $errors;
	private $data;
	
	private $clean_data = array();

	
	public function __construct(array $data = NULL) {
		$this->clean_data = array();
		
		$this->configure();
		$this->data = $data;
		if ($this->isBound())
			$this->validate($data);
		
		$this->inspect();
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
	
	public function validate() {
		
		$this->clean_data = array();
		
		foreach ($this->fields as $key => $field) {
				
			try {
				$this->clean_data[$key] = $field->clean(
					$field->widget()->value($this->data, $key, NULL));
			
			} catch (FormValidationException $e) {
				$this->errors[$key] = $e->errors();
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
		
		if (isset($this->clean_data[$key]))
			return $this->clean_data[$key];
			
		
		if (isset($this->fields[$key]))
			throw new FormException("Field '{$key}' is not defined");
			
		throw new FormException("Unknown field '{$key}'");
	}
	
	public static function create(array $specification, array $data = NULL) {
		return new ConcreteForm($specification, $data);
	}
	
	
}

