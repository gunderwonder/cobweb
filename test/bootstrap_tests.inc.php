<?php
if (!defined('COBWEB_DIRECTORY'))
	define('COBWEB_DIRECTORY', realpath(dirname(__FILE__) . '/../'));


set_include_path(get_include_path() . ':' . COBWEB_DIRECTORY . '/vendor/phpunit/');

require_once 'PHPUnit/Framework.php';


class CobwebTestSuite extends PHPUnit_Framework_TestSuite {
	
 
    protected function setUp() {
	
    }
 
    protected function tearDown() {
    }
}


if (!defined('PHPUnit_MAIN_METHOD'))
   define('PHPUnit_MAIN_METHOD', 'CobwebTest::main');


require_once dirname(__FILE__) . '/core/cobweb_configuration.test.php';

class CobwebTestSuiteLoader {
	
	public static function main() {
		// PHPUnit_TextUI_TestRunner::run(self::suite(), array(
		// 	'printer' => new PHPUnit_Util_Log_TAP()
		// ));
	}
	
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
			&& $file->getFilename() != 'fixtures')
				new CobwebTestSuiteLoader($this->parent_suite, $file->getPathname());
	}
	
	protected function loadTestCases() {
		foreach (glob($this->directory . '/*.test.php') as $test_file) {
			require_once $test_file;
			$this->suite->addTestSuite($this->classify(basename($test_file)));
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

if (PHPUnit_MAIN_METHOD == 'CobwebTest::main');
	CobwebTestSuiteLoader::main();

