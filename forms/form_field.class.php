<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Forms
 * @version    $Revision$
 */
abstract class FormField {
	
	private $defaults = array(
		'required' => true,
		'initial' => ''
	);
	
	private $error_messages = array(
		'required' => "'%s' is required"
	);
	
	private $label;
	
	public function __construct(array $specification = array(),
		                        FormWidget $widget = NULL) {
			
		$this->specification = new ImmutableArray(array_merge($this->defaults, $specification));
		$this->widget = is_null($widget) ? $this->defaultWidget() : $widget;
		
		// merge default error messages with the concrete class' messages
		$this->error_messages = array_merge($this->error_messages, 
			$this->errorMessages());
			
		// merge error message with specification if provided
		if (isset($this->specification['error_messages'])) {
			$this->error_messages = array_merge(
				$this->error_messages, 
				$this->specification['error_messages']);
			// unset($this->specification['error_messages']);
		}
	}
	
	public function clean($value) {
		if (empty($value) && $this->isRequired())
			$this->error('required');
		
		return (string)$value;
	}
	
	public static function propertize($field_name) {
		return str_replace('-', '_', $field_name);
	}
	
	
	public static function HTMLize($field_name) {
		return str_replace('_', '-', $field_name);
	}
	
	public static function labelize($field_name) {
		return ucfirst(str_replace('_', ' ', $field_name));
	}
	
	public function label() {
		if ($label = $this->specification->get('label', NULL))
			return $label;
		return self::labelize($this->name);
	}
	
	public function defaultWidget() {
		return new TextInput();
	}
	
	public function widget() {
		return $this->widget;
	}
	
	public function isRequired() {
		return $this->specification['required'];
	}
	
	public function setForm(Form $form) {
		$this->form = $form;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function error($error_message_key, array $arguments = NULL) {
		if (is_null($arguments))
			$arguments = array($this->label());
			
		throw new FormValidationException(
			array(call_user_func_array(
				  	'sprintf', 
			      	array_merge(array($this->error_messages[$error_message_key]), 
			      	            $arguments)
				)
			)
		);
	}
	
	public function errorMessage($key) {
		return $this->error_messages[$key];
	}
	
	protected function errorMessages() {
		return array();
	}
	
	public function __toString() {
		return $this->widget()->render();
	}


}
