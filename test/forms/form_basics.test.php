<?php
require_once dirname(__FILE__) . '/support/test_form.class.php';

class FormsBasicsTest extends CobwebCoreTestCase {
	
	public function setUp() {
		$this->unbound_form = new TestForm();
		$this->bound_form = new TestForm(
			array(
				'username' => 'gunderwonder',
				'number' => '10',
				'negative_number' => '-200'
			)
		);
	
	}
	
	public function testFormBoundness() {
		$this->assertFalse($this->unbound_form->isBound());
		$this->assertTrue($this->bound_form->isBound());
	}
	
	public function testFormIsValid() {
		$this->assertTrue($this->bound_form->isValid());
		$this->assertFalse($this->unbound_form->isValid());

	}
	
	public function testTextNormalization() {
		$this->assertIdentical($this->bound_form->username, 'gunderwonder');
	}
	
	public function testIntegerNormalization() {
		$this->assertIdentical($this->bound_form->number, 10);
		$this->assertIdentical($this->bound_form->negative_number, -200);
	}
}
