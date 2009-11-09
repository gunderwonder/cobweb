<?php

class QValueTest extends CobwebTestCase {
	
	public function test() {
		$this->assertEquals(
			http_parse_qvalues('image/gif, image/jpeg, text/html'),
			array(
				'1' => array('image/gif', 'image/jpeg', 'text/html')
			)
		);
		
		$this->assertEquals(
			http_parse_qvalues('image/gif;q=1.0, image/jpeg;q=0.8, image/png; q=1.0,*;q=0.1'),
			array(
				'1' => array('image/gif', 'image/png'),
				'0.8' => array('image/jpeg'),
				'0.1' => array('*')
			)
		);
		
		$this->assertEquals(
			http_parse_qvalues('gzip	;q=1.0, identity; q=0.5, *  ;q=0'),
			array(
				'1' => array('gzip'),
				'0.5' => array('identity'),
				'0' => array('*')
			)
		);
	}
}