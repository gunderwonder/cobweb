<?php

abstract class InputWidget extends FormWidget {
	
	public function render(FormField $field, $value, $attributes = array()) {
		$attributes = array_merge($this->attributes, 
			$attributes, 
			array('type' => $this->inputType(), 'name' => $field->name())
		);
		if (!is_null($value))
			$attributes['value'] = $value;
		if ($id = $field->id())
			$attributes['id'] = $id;
		$element_attributes = html_flatten_attributes($attributes);
		return "<input{$element_attributes} />";
	}
	
	/**
	 * Subclasses must provide the value of the HTML `type` 
	 * attribute of this input element.
	 * @return string
	 */
	abstract public function inputType();
	
}

class TextInput extends InputWidget {

	public function inputType() {
		return 'text';
	}	
}

class PasswordInput extends InputWidget {

	public function inputType() {
		return 'password';
	}
}

class HiddenInput extends InputWidget {

	public function inputType() {
		return 'password';
	}	
}

class TextareaInput extends FormWidget {
	
	public function render(FormField $field, $value, $attributes = array()) {
		$attributes = array_merge(
			array('cols' => 40, 'rows' => 10, 'name' => $field->name()),
			$this->attributes, 
			$attributes
		);
		if ($id = $field->id())
			$attributes['id'] = $id;
		$escaped_value = html_escape($value);
		$element_attributes = html_flatten_attributes($attributes);
		return "<textarea{$element_attributes}>{$escaped_value}</textarea>";
	}
}

class CheckboxInput extends InputWidget {
	
	protected $checked = false;
	
	public function render(FormField $field, $value, $attributes = array()) {
		$attributes = array_merge($attributes, $this->checked ? array('checked' => 'checked') : array());
		return parent::render($field, $value, $attributes);
	}
	
	public function extract(FormField $field, $data) {
		return ($this->checked = isset($data[$field->name()]));
	}

	public function inputType() {
		return 'checkbox';
	}
}