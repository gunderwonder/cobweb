<?php

class StringUtilitiesTest extends PHPUnit_Framework_TestCase {
	
	public function setUp() {
		require_once COBWEB_DIRECTORY . '/vendor/utf8/utf8.php';
		require_once COBWEB_DIRECTORY . '/utilities/string.inc.php';
	}
	
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
		$this->assertEquals(rstrip('Cobweb', 'web'), 'Cob');
		$this->assertEquals(rstrip('Cobweb', ''), 'Cobweb');
		$this->assertEquals(rstrip('Cobweb Framework', 'work'), 'Cobweb Frame');
		$this->assertEquals(rstrip('Øystein Riiser Gundersen', 'Riiser Gundersen'), 'Øystein ');
	}
	
	public function testLStrip() {
		$this->assertEquals(lstrip('Cobweb', 'Cob'), 'web');
		$this->assertEquals(lstrip('Cobweb', ''), 'Cobweb');
		$this->assertEquals(lstrip('Cobweb Framework', 'Cobweb '), 'Framework');
	}

}