<?php

class ArrayClassesTest extends CobwebCoreTestCase {

	public function testImmutableArray() {
		$a = new ImmutableArray(array('a' => 0, 'b' => 1, 'c' => 2));
		
		$this->assertEqual($a->keys(), array('a', 'b', 'c'));
		$this->assertEqual($a->values(), array(0, 1, 2));
		
		// TODO: throw exception in ImmutableArray when attempting to mutate?
		$a['d'] = 2;
		$this->assertFalse(isset($a['d']), 'Immutable array should not change from orinial array');
		
		$this->assertTrue(isset($a['a']));
		$this->assertEqual($a['a'], 0);
		
		$this->assertEqual($a->get('z', -10), -10);
	}
	
	public function testMutableArray() {
		$a = new MutableArray(array('a' => 0, 'b' => 1, 'c' => 2));
		
		$this->assertEqual($a->keys(), array('a', 'b', 'c'));
		$this->assertEqual($a->values(), array(0, 1, 2));
		
		$a['a'] = 1;
		$this->assertEqual($a['a'], 1);
		$this->assertEqual($a->get('z', -10), -10);
	}
	
}