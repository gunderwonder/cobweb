<?php


class TextFieldForm1 extends Form {
	public function configure() {
		$this->field = new TextField();
	}
}


class TextFieldTest extends CobwebCoreTestcase {
	
	
	public function setUp() {
		$this->form_1 = new TextFieldForm1(
			array(
				'field' => 'some text, biatch'
			)
		);
	}
	
}