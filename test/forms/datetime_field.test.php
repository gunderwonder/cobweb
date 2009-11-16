<?php

class DateTimeFieldTest extends CobwebTestCase {
	
	/**
	 * @dataProvider dateTimes
	 */
	public function testDateTimeField($value, $expected, $should_validate) {
		$field = new DateTimeField();
		
		if ($should_validate) {
			$this->assertEquals($field->clean($value), $expected);
		} else {
			try {
				$field->clean($value);
				$this->fail('Invalid datetime should throw validation exception');
			} catch (FormValidationException $e) {
				$this->assertEquals($e->messages(), array(__('Enter a valid date/time.')));
			}
		}
	}
	
	public function dateTimes() {
		return array(
			array(CWDateTime::create(2006, 10, 25), CWDateTime::create(2006, 10, 25), true),
			array(CWDateTime::create(2006, 10, 25, 14, 30), CWDateTime::create(2006, 10, 25, 14, 30), true),
			array(CWDateTime::create(2006, 10, 25, 14, 30, 59), CWDateTime::create(2006, 10, 25, 14, 30, 59), true),
			array('2006-10-25 14:30:45', CWDateTime::create(2006, 10, 25, 14, 30, 45), true),
			array('2006-10-25 14:30:00', CWDateTime::create(2006, 10, 25, 14, 30), true),
			array('2006-10-25 14:30', CWDateTime::create(2006, 10, 25, 14, 30), true),
			array('2006-10-25', CWDateTime::create(2006, 10, 25, 0, 0), true),
			array('10/25/2006 14:30:45', CWDateTime::create(2006, 10, 25, 14, 30, 45), true),
			array('10/25/2006 14:30:00', CWDateTime::create(2006, 10, 25, 14, 30), true),
			array('10/25/2006', CWDateTime::create(2006, 10, 25, 0, 0), true),
			array('10/25/06 14:30:45', CWDateTime::create(2006, 10, 25, 14, 30, 45), true),
			array('10/25/06 14:30:00', CWDateTime::create(2006, 10, 25, 14, 30), true),
			array('10/25/06 14:30', CWDateTime::create(2006, 10, 25, 14, 30), true),
			array('10/25/06', CWDateTime::create(2006, 10, 25, 0, 0), true),
			array('hello', NULL, false),
			array('10/25/06hello', NULL, false),
			array('10/25/2006 14:30:45a', NULL, false)
		);
	}
	
	public function testEmptyDateTimeField() {
		$field = new DateTimeField(array('required' => false));
		$this->assertEquals($field->clean(NULL), NULL);
	}
	
	public function testCustomInputFormat() {
		$field = new DateTimeField(array('input_formats' => array('%Y %m %d %I:%M %p')));
		$this->assertEquals(
			$field->clean(CWDateTime::create(2006, 10, 25)),
			CWDateTime::create(2006, 10, 25)
		);
		
		$this->assertEquals(
			$field->clean('2006 10 25 2:30 PM'),
			CWDateTime::create(2006, 10, 25, 14, 30)
		);
		
		try {
			$field->clean('2006-10-25 14:30:45');
			$this->fail('Invalid datetime should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Enter a valid date/time.')));
		}
		
	}
	
}