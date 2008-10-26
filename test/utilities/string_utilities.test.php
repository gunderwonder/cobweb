<?php

class StringUtilitiesTest extends CobwebCoreTestCase {
	
	
	public function testStringStartsWith() {
		$this->assertTrue(str_starts_with('Cobweb', 'C'));
		$this->assertFalse(str_starts_with('C', 'Cobweb'));
		
		$this->assertTrue(str_starts_with('Cobweb', 'Cobweb'));
		$this->assertTrue(str_starts_with('Cobweb', 'Cob'));
		
		$this->assertTrue(str_starts_with('ØYSTEIN', 'Ø'));
		
		$this->assertFalse(str_starts_with('', 'Cobweb'));
		$this->assertFalse(str_starts_with('', ''));
	}
	
	public function testStringEndsWith() {
		$this->assertTrue(str_ends_with('Cobweb', 'web'));
		$this->assertFalse(str_ends_with('C', 'Cobweb'));
		
		$this->assertTrue(str_ends_with('Cobweb', 'Cobweb'));
		
		$this->assertFalse(str_ends_with('', 'Cobweb'));
		$this->assertFalse(str_ends_with('', ''));
		$this->assertFalse(str_ends_with('Cobweb', ''));
		
		$this->assertTrue(str_ends_with('Øystein ÅØØØØØ', 'ÅØØØØØ'));
	}
	
	public function testRStrip() {
		$this->assertEqual(rstrip('Cobweb', 'web'), 'Cob');
		$this->assertEqual(rstrip('Cobweb', ''), 'Cobweb');
		$this->assertEqual(rstrip('Cobweb Framework', 'work'), 'Cobweb Frame');
	}
	
	public function testLStrip() {
		$this->assertEqual(lstrip('Cobweb', 'Cob'), 'web');
		$this->assertEqual(lstrip('Cobweb', ''), 'Cobweb');
		$this->assertEqual(lstrip('Cobweb Framework', 'Cobweb '), 'Framework');
	}
	
}