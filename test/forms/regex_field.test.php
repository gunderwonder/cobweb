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
	
	/**
	 * @dataProvider slugs
	 */
	public function testSlugField($value, $should_be_valid) {
		$field = new SlugField();
		
		if ($should_be_valid)
			$this->assertEquals($field->clean($value), $value);
		else {
			try {
				$field->clean($value);
				$this->fail('Invalid slug should throw validation exception');
			} catch (FormValidationException $e) {
				$this->assertEquals($e->messages(), array(
					__(
						"Enter a valid 'slug' consisting of letters, " .
						"numbers, uderscores or hyphens."
					)
				));
			}
		}
	}
	
	public function slugs() {
		return array(
			array('^&!', false),
			array('a-nice-valid-slug', true),
			array('øystein-riiser-gunderseon', false) // XXX: locale
		);
	}
	
	/**
	 * @dataProvider slugifiedSlugs
	 */
	public function testSlugFieldWithSlugifier($value, $normalized, $should_be_valid) {
		$field = new SlugField(array('slugify_value' => true));
		
		if ($should_be_valid)
			$this->assertEquals($field->clean($value), $normalized);
		else {
			try {
				$field->clean($value);
				$this->fail('Invalid slug should throw validation exception');
			} catch (FormValidationException $e) {
				$this->assertEquals($e->messages(), array(
					__(
						"Enter a valid 'slug' consisting of letters, " .
						"numbers, uderscores or hyphens."
					)
				));
			}
		}
	}
	
	public function slugifiedSlugs() {
		return array(
			array('^&!', NULL, false),
			array('a-nice-valid-slug', 'a-nice-valid-slug', true),
			array('øystein-riiser-gundersen', 'oystein-riiser-gundersen', true),
			array('øystein--riiser--gundersen', 'oystein-riiser-gundersen', true),
			array('Øystein Riiser Gundersen', 'oystein-riiser-gundersen', true),
			array('Øystein  Riiser  Gundersen', 'oystein-riiser-gundersen', true),
		);
	}
}
