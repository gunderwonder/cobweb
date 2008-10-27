<?php

class TextInput extends FormWidget {
	public function render($data = NULL) {
		$data = is_null($data) ? $this->initialData() : $data;
		return "<input type=\"text\" value=\"{$data}\" />";
	}
}