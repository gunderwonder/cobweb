<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

abstract class Form implements IteratorAggregate, ArrayAccess {
	
	protected $form_fields = array();
	protected $errors = array();
	protected $form_data = array();
	protected $clean_data = array();
	
	protected $id_format;
	
	public function __construct($form_data = NULL, array $properties = array()) {
		$properties = new ImmutableArray($properties);
		$this->id_format = $properties->get('id_format', 'id_%s');
		
		$this->form_data = !is_null($form_data) ? new ImmutableArray($form_data) : NULL;
		
		$this->configure();
		$this->clean_data = $this->isBound() ? $this->clean() : NULL;
		if ($this->isBound()) $this->formClean();
	}
	
	/**
	 * @return array
	 */
	protected function clean() {
		$clean_data = array();
		foreach ($this as $name => $field) {
			$data = $field->widget()->extract($field, $this->form_data);
			try {
				$clean_data[$name] = $field->clean($data);
			} catch (FormValidationException $e) {
				$this->errors[$name] = $e->messages();
			}
		}
		return $clean_data;
	}
	
	protected function formClean() {	
		foreach ($this as $name => $field) {
			if (!isset($this->clean_data[$name]))
				continue;
			$cleaner_method_name = 'clean' . str_replace('_', '', $name);
			try {
				if (method_exists($this, $cleaner_method_name))
					$this->clean_data[$name] = call_user_func(
						array($this, $cleaner_method_name), 
						$this->clean_data[$name]
					);
			} catch (FormValidationException $e) {
				$this->errors[$name] = $e->messages();
			}
		}
	}
	
	/**
	 * @return bool
	 */
	public function isBound() {
		return !is_null($this->form_data);
	}
	
	/**
	 * 
	 */
	abstract protected function configure();
	
	/**
	 * @return array
	 */
	public function errors() {
		return $this->errors;
	}
	
	/**
	 * @param string $name
	 * @return array
	 */
	public function error($name) {
		return isset($this->errors[$name]) ? $this->errors[$name] : array();
	}
	
	public function identify($name) {
		return sprintf($this->id_format, $name);
	}
	
	/**
	 * @return bool
	 */
	public function isValid() {
		return $this->isBound() && empty($this->errors);
	}
	
	/**
	 * @param string $key
	 * @param FormField $field
	 */
	public function __set($key, $field) {
		if ($field instanceof FormField) {
			$field->bindToForm($this, $key);
			$this->form_fields[$key] = $field;
		} else
			throw new InvalidArgumentException('Can only assingn form field objects to form');
	}

	/**
	 * @param string $key
	 * @return FormField
	 */
	public function __get($key) {
		if (!isset($this->form_fields[$key]))	
			throw new InvalidArgumentException("Unknown field '{$key}'");
		return $this->form_fields[$key];
	}
	
	public function __unset($key) {
		unset($this->form_fields[$key]);
	}
	
	public function __isset($key) {
		return isset($this->form_fields[$key]);
	}
	
	/**
	 * @return array
	 */
	public function cleanData() {
		return $this->clean_data;
	}
	
	// ArrayAccess implementation
	public function offsetExists($offset) {
		return isset($this->clean_data[$offset]);
	}
	
	public function offsetGet($offset) {
		return $this->clean_data[$offset];
	}
	
	public function offsetSet($offset, $value) {
		throw new Exception('Cannot change clean form data');
	}
	
	public function offsetUnset($offset) {
		throw new Exception('Cannot unset clean form data');
	}
	
	public function data() {
		return $this->form_data;
	}
	
	/**
	 * @param string $key
	 * @return Iterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->form_fields);
	}
	
	public static function create(array $specification, $data = NULL) {
		return new AnonymousForm($specification, $data);
	}
}

final class AnonymousForm extends Form {
	
	public function __construct(array $specification, $data = NULL, array $properties = array()) {
		foreach ($specification as $key => $field)
			$this->__set($key, $field);		
		parent::__construct($data, $properties);
	}
	
	public function configure() { }
}