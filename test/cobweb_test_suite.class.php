<?php

class CobwebTestSuite extends PHPUnit_Framework_TestSuite {
	
	public function setUp() {
		if (!defined('COBWEB_DIRECTORY'))
			define('COBWEB_DIRECTORY', realpath(dirname(__FILE__) . '/../'));
		require_once COBWEB_DIRECTORY . '/core/cobweb_bootstrap.inc.php';
	}
	
	public function tearDown() {
		
	}
}