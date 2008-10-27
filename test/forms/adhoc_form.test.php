<?php

class AdhocFormTest extends CobwebCoreTestcase {
	
	public function testAdhocBasics() {
		$form = Form::create(array(
			'name' => new TextField(),
			'number' => new IntegerField()
		));
		
		$this->assertFalse($form->isBound());
		$this->expectException('FormException');
		$form->name;
	}
	
	public function testAdhocBindingAndValidation() {
		$form = Form::create(array(
			'name' => new TextField(),
			'number' => new IntegerField()
		), array('name' => 'gunderwonder', 'number' => 'text'));
		
		$this->assertTrue($form->isBound());

		$this->assertEqual($form->name, 'gunderwonder');
		$this->assertFalse($form->isValid());
		$this->assertEqual($form->errors(), array('number' => array("'Number' is not a valid integer")));
	}
	
}