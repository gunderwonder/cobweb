<?php

class Value {
	public function __construct($value) {
		$this->value = $value;
	}
}

class ArrayUtilitiesTest extends CobwebCoreTestCase {
	
	public function testArrayStartsWith() {
		$this->assertTrue(array_starts_with(array(1, 2, 3), array(1)));
		$this->assertTrue(array_starts_with(array(1, 2, 3), array(1, 2, 3)));
		
		$this->assertFalse(array_starts_with(array(1, 2, 3), array(3, 2, 1)));
		$this->assertFalse(array_starts_with(array(1, 2, 3), array(1, 3, 2)));
	}
	
	public function testArrayLStrip() {
		// FIXME: array_lstrip() does not work as advertised
		// $this->assertEqual(array_lstrip(array(1, 2, 3), array(1, 2)), array(3));
	}
	
	public function testArrayToSQLList() {
		$array = array(1, 3, 4, 5);
		$this->assertEqual(array_to_sql_list($array), '(1, 3, 4, 5)');
		
		// TODO: add some error handling to array_to_sql_list()
		// $array = array('a' => 1, 'b' => 3, 'c' => 4, 5);
		// $this->dump(array_to_sql_list($array));
		// $this->assertEqual(array_to_sql_list($array, 'a'), '(1, 3, 4, 5)');

		$array = array(new Value(1), new Value(2), new Value(3));
		$this->assertEqual(array_to_sql_list($array, 'value'), '(1, 2, 3)');
	}
	
	public function testArrayWithoutKeys() {
		$array = array('a' => 1, 2, 3);

		// FIXME: array_without_keys() does not work with heterogenous arrays
		// $this->assertEqual(array_without_keys($array, array('a')), array(2, 3));
		
		$array = array('a' => 1, 'b' => 2, 'c' => 3);
		$this->assertEqual(array_without_keys($array, array('a')), array('b' => 2, 'c' => 3));
	}
	
	public function testArrayWithoutInDices() {
		$array = array('a' => 1, 2, 3);

		// FIXME: array_without_keys() does not work with heterogenous arrays
		// $this->assertEqual(array_without_indices($array, array(0)), array('a' => 1, 3));
		
		$array = array(1, 2, 3);
		$this->assertEqual(array_without_indices($array, array(0)), array(2, 3));
		$this->assertEqual(array_without_indices($array, array(0, 2)), array(2));
	}
	
	
	public function testArrayIndexOfKey() {
		$array = array('a' => 1, 2, 3);
		
		// FIXME: array_index_of_key() is meaningless for heterogenous arrays
		$this->assertEqual(array_index_of_key($array, 'a'), 0);
		$this->assertEqual(array_index_of_key($array, 0), 0); 
		
		$array = array('a' => 1, 'b' => 2, 'c' => 3);
		$this->assertEqual(array_index_of_key($array, 'c'), 2); 
		$this->assertEqual(array_index_of_key($array, 'a'), 0); 
	}
	
}
