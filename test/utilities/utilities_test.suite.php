<?php

class UtilitiesTestSuite extends CobwebTestSuite {
	
	public function __construct() {
		parent::__construct('Utilities Test Suite');
		$this->load(dirname(__FILE__));
	}
	
}