<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Tests
 * @version $Revision$
 */
class StringUtilitiesTest extends CobwebTestCase {
	
	public function testHTTPFlattenAttributes() {
		$this->assertEquals(html_flatten_attributes(array(
			'type' => 'text',                       // simple attribute
			'value' => '"iñtërnâtiônàlizætiøn&<>"', // UTF-8
			'class' => array('required')            // non-scalar
		)), ' type="text" value="&quot;iñtërnâtiônàlizætiøn&amp;&lt;&gt;&quot;" class="required"');
		
		$this->assertEquals(html_flatten_attributes(array(
			'type' => 'text',
			'value' => '"iñtërnâtiônàlizætiøn&<>"',
			'class' => array('required clearer')
		)), ' type="text" value="&quot;iñtërnâtiônàlizætiøn&amp;&lt;&gt;&quot;" class="required clearer"');
	}
	
	public function testInString() {
		$this->assertTrue(in_string('text', 'texttext'));
		$this->assertFalse(in_string('a', 'texttext'));
		$this->assertTrue(in_string('iñtërnâ', 'iñtërnâtiônàlizætiøn'));
	}
	
	public function testUpperCaseFirst() {
		// the day this fails, we can use ucfirst() safely!
		$this->assertNotEquals(ucfirst('øystein'), 'Øystein');
		
		$this->assertEquals(utf8_ucfirst('øystein'), 'Øystein');
		$this->assertEquals(utf8_ucfirst('ascii'), 'Ascii');
		$this->assertEquals(utf8_ucfirst('ñino'), 'Ñino');
	}
	
	public function echoFoo() {
		echo 'foo';
	}
	
	public function echoArgument($argument) {
		echo $argument;
	}
	
	public function testOutputBufferingCall() {
		$this->assertEquals(call_with_output_buffering(array($this, 'echoFoo')), 'foo');
		$this->assertEquals(call_with_output_buffering(array($this, 'echoArgument'), 'foo'), 'foo');
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