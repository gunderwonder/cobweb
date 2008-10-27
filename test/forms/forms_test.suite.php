<?php

class FormsTestSuite extends CobwebTestSuite {
	
	public function __construct() {		
		parent::__construct('Forms Test Suite');
		$this->load(dirname(__FILE__));
	}
	
}