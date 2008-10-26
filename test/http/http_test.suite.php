<?php

class HTTPTestSuite extends CobwebTestSuite {
	
	public function __construct() {		
		parent::__construct('HTTP Test Suite');
		$this->load(dirname(__FILE__));
	}
	
}