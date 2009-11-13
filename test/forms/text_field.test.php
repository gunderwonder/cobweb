<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class ToStringClass {
	public function __toString() {
		return 'Hellz yeah.';
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Tests
 * @version $Revision$
 */
class TextFieldTest extends CobwebTestCase {
	
	public function testRequiredTextField() {
		$field = new TextField();
		$this->assertEquals($field->clean('hello'), 'hello');
		
		try {
			$this->assertEquals($field->clean(NULL), 'hello');
			$this->fail('Null required value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('This field is required.')));
		}
		
		try {
			$this->assertEquals($field->clean(''), 'hello');
			$this->fail('Empty required value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('This field is required.')));
		}
	}
	
	public function testNotRequiredTextField() {
		$field = new TextField(array('required' => false));
		$this->assertSame($field->clean(1), '1', 'Integer should normalize to string');
		
		$this->assertEquals($field->clean('hello'), 'hello');
		$this->assertSame($field->clean(NULL), '', 'Null values should normalize to the empty string');
		
		$this->assertEquals($field->clean('hello'), 'hello');
		$this->assertSame($field->clean(NULL), '', 'Null values should normalize to the empty string');
		
		$this->assertEquals($field->clean('hello'), 'hello');
		$this->assertSame($field->clean(''), '');
		
		try {
			$this->assertEquals($field->clean(array()), 'hello');
			$this->fail('Array value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Enter a valid value.')));
		}
		
		try {
			$this->assertEquals($field->clean($this), 'hello');
			$this->fail('Objects without __toString() should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Enter a valid value.')));
		}
		
		$this->assertEquals($field->clean(new ToStringClass()), 'Hellz yeah.',
			'Objects with __toString() should normalize to its string value'
		);
	}
	
	public function testMaxLength() {
		$field = new TextField(array('max_length' => 10, 'required' => false));
		
		$this->assertEquals($field->clean('12345'), '12345');
		
		$this->assertEquals($field->clean('1234567890'), '1234567890');
		
		try {
			$this->assertEquals($field->clean('1234567890a'), '1234567890a');
			$this->fail('Values longer than `max_length` should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Ensure this value has at most 10 characters (it has 11).')));
		}	
	}
	
	public function testMinLength() {
		$field = new TextField(array('min_length' => 10, 'required' => false));
		$this->assertEquals($field->clean(''), '');
		
		try {
			$field->clean('12345');
			$this->fail('Values shorter than `max_length` should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Ensure this value has at least 10 characters (it has 5).')));
		}
		
		$this->assertEquals($field->clean('1234567890'), '1234567890');
		$this->assertEquals($field->clean('1234567890a'), '1234567890a');
	}
	
	public function testRequiredAndMinLength() {
		$field = new TextField(array('min_length' => 10, 'required' => true));
		try {
			$this->assertEquals($field->clean(''), 'hello');
			$this->fail('Empty required value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('This field is required.')));
		}
		
		try {
			$field->clean('12345');
			$this->fail('Values shorter than `max_length` should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Ensure this value has at least 10 characters (it has 5).')));
		}
	}
	
}