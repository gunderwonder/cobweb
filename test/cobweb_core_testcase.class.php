<?php

abstract class CobwebCoreTestCase extends UnitTestCase {

	private $is_initialized = false;

	public function initialize($test_application = NULL) {
		if (!defined('COBWEB_PROJECT_DIRECTORY'))
			define('COBWEB_PROJECT_DIRECTORY', COBWEB_DIRECTORY . '/test/support/test_application');
		
		if (!$this->is_initialized) {
			Cobweb::initialize();
			$this->is_initialized = true;
		}
			
	}
}