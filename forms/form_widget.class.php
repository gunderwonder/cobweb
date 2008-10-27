<?php

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