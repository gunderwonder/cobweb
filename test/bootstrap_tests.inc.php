<?php
if (!defined('COBWEB_DIRECTORY'))
	define('COBWEB_DIRECTORY', realpath(dirname(__FILE__) . '/../'));
if (!defined('COBWEB_PROJECT_DIRECTORY'))
	define('COBWEB_PROJECT_DIRECTORY', COBWEB_DIRECTORY . '/test/support/testy');

require_once 'PHPUnit/Framework.php';
require_once COBWEB_DIRECTORY . '/core/cobweb_bootstrap.inc.php';

if (!defined('PHPUnit_MAIN_METHOD'))
   define('PHPUnit_MAIN_METHOD', 'CobwebTest::main');

Cobweb::initialize()->setup();
Doctrine::loadData(COBWEB_PROJECT_DIRECTORY . '/data/fixtures');

class CobwebTestSuiteLoader {
	
	public static function main() { }
	
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('Cobweb Test Suite');
		$loader = new CobwebTestSuiteLoader($suite, NULL);
		return $suite;
	}
	
	public function __construct($suite, $directory = NULL) {
		$is_top_level = is_null($directory);
		
		$this->parent_suite = $suite;
		$this->suite = $is_top_level ? $suite : new PHPUnit_Framework_TestSuite((string)$directory);
		$this->directory = $is_top_level ? dirname(__FILE__) : $directory;
		
		$this->loadTestSuites();
		$this->loadTestCases();
		
		if (!$is_top_level)
			$this->parent_suite->addTest($this->suite);
	}
	
	protected function loadTestSuites() {
		foreach (new DirectoryIterator($this->directory) as $file)
			if ($file->isDir() 
			&& !$file->isDot() 
			&& strpos($file->getFilename(), '.') !== 0 
			&& $file->getFilename() != 'support')
				new CobwebTestSuiteLoader($this->parent_suite, $file->getPathname());
	}
	
	protected function loadTestCases() {
		foreach (glob($this->directory . '/*.test.php') as $test_file) {
			$class_name = $this->classify(basename($test_file));
			CobwebLoader::register($class_name, $test_file);
			$this->suite->addTestSuite($class_name);
		}
	}
	
	protected function classify($test_file) {
		$basename = substr($test_file, 0, strpos($test_file, '.test.php'));
		return preg_replace_callback('/[_-](\w)/',
			create_function('$x', 'return ucfirst($x[1]);'),
			ucfirst($basename)
		) . 'Test';
	}	
	
}