<?php

class IOTest extends CobwebCoreTestcase {
	
	
	public function testFileOpening() {
		$file = new File(dirname(__FILE__) . '/../support/test.txt');
	}
	
	
}