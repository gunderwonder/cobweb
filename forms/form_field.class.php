<?php

class FormValidationException extends UnexpectedValueException {
	protected $messages = array();
	
	public function __construct($messages) {
		$this->messages = is_string($messages) ? array($messages) : $messages;
		
		parent::__construct(implode(', ', $this->messages));
	}
	
	public function messages() {
		return $this->messages;
	}
}

abstract class FormField {
	
	protected $properties = array();
	protected $form = NULL;
	protected $name = NULL;

	public function __construct(array $properties = array()) {
		
		$properties = array_merge($this->defaultProperties(), $properties);
		$properties = new ImmutableArray($properties);
		$this->required = $properties->get('required', true);
		$this->widget = $properties->get('widget', new TextInput());
		$this->label = $properties->get('label', '');
		
		$this->error_messages = array_merge(
			array(
				'required' => __('This field is required.'),
        		'invalid' => __('Enter a valid value.'),
			),
			$properties->get('error_messages', array())
		);
		
		$this->properties = $properties;
		$this->initialize();
	}
	
	protected function initialize() { }
	
	public function isRequired() {
		return $this->required;
	}
	
	public function label() {
		return $this->label;
	}
	
	public function widget() {
		return $this->widget;
	}
	
	/**
	 * @throws FormValidationException
	 */
	public function clean($value) {
		if ($this->required && in_array($value, array(NULL, '')))
			throw new FormValidationException($this->error_messages['required']);
		return $value;
	}
	
	protected function defaultProperties() {
		return array();
	}
	
	public function name() {
		if (!$this->isBoundToForm())
			throw new CobwebException('This field is not bound to any form and is not named');
		return $this->name;
	}
	
	public function bindToForm(Form $form, $name) {
		$this->form = $form;
		$this->name = $name;
		if (!$this->label)
			$this->label = $this->createLabel($name);
	}
	
	protected function isBoundToForm() {
		return !is_null($this->form);
	}
	
	public function id() {
		if (!$this->isBoundToForm())
			return '';
			
		// form handles ids
		return $this->form->identify($this->name);
	}
	
	public function renderLabel($attributes = array()) {
		if (!$this->isBoundToForm())
			throw new CobwebException('This field is not bound to any form and cannot be rendered');
		return $this->widget()->renderLabel($this, $attributes);
	}
	
	public function render($attributes = array()) {
		
		if (!$this->isBoundToForm())
			throw new CobwebException('This field is not bound to any form and cannot be rendered');
		
		$data = $this->form->isBound() ? $this->form->data()->get($this->name, '') : '';
		return $this->widget()->render($this, $data, $attributes);
	}
	
	public function __toString() {
		try {
			return $this->render();
		} catch (Exception $e) {
			return '';
		}
	}
	
	protected function createLabel($name) {
		return str_replace('_', ' ', utf8_ucfirst($name));
	}
	
	protected function message() {
		$arguments = func_get_args();
		$message_type = array_shift($arguments);
		$message = $this->error_messages[$message_type];
		if (!empty($arguments))
			$message = vsprintf($message, $arguments);
		return $message;
	}
	
	public function isValid() {
		return !is_null($this->form) ? !(bool)$this->form->error($this->name()) : true;
	}
	
	public function errors() {
		return !is_null($this->form) ? $this->form->error($this->name()) : array();
	}

}