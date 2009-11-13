<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Tests
 * @version $Revision$
 */
class RegexFieldTest extends CobwebTestCase {
	
	public function testSimpleRegexField() {
		$field = new RegexField('/^\d[A-F]\d$/');
		
		$this->assertEquals($field->clean('2A2'), '2A2');
		$this->assertEquals($field->clean('3F3'), '3F3');
		
		try {
			$field->clean('3G3');
			$this->fail('Non-matching value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(
				__('Enter a valid value.')
			));
		}
		
		try {
			$field->clean(' 2A2');
			$this->fail('Non-matching value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(
				__('Enter a valid value.')
			));
		}

		try {
			$field->clean('2A2 ');
			$this->fail('Non-matching value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(
				__('Enter a valid value.')
			));
		}
		
		try {
			$field->clean('');
			$this->fail('Empty required value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('This field is required.')));
		}
	}
	
	public function testRegexFieldWithCustomMessage() {
		$field = new RegexField('/^\d\d\d\d$/', array(
			'error_messages' => array('invalid' => 'Enter a four-digit number.')
		));
		
		$this->assertEquals($field->clean('1234'), '1234');
		
		try {
			$field->clean('123');
			$this->fail('Invalid value should throw validation exception with custom error message');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array('Enter a four-digit number.'));
		}
		
		try {
			$field->clean('abcd');
			$this->fail('Invalid value should throw validation exception with custom error message');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array('Enter a four-digit number.'));
		}
	}
	
	public function testRegexFieldWithLengthValidation() {
		$field = new RegexField('/^\d+$/', array('min_length' => 5, 'max_length' => 10));
		try {
			$field->clean('123');
			$this->fail('Values shorter than `min_length` should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Ensure this value has at least 5 characters (it has 3).')));
		}
		
		try {
			$field->clean('abc');
			$this->fail('Values shorter than `min_length` should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Ensure this value has at least 5 characters (it has 3).')));
		}
		
		$this->assertEquals($field->clean('12345'), '12345');
		$this->assertEquals($field->clean('1234567890'), '1234567890');
		
		try {
			$field->clean('12345678901');
			$this->fail('Values longer than `max_length` should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Ensure this value has at most 10 characters (it has 11).')));
		}
		
	}
}
