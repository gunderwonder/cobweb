<?php

class RunCobwebTestsCommand extends CobwebManagerCommand {
	
	public function initialize() {
		require_once dirname(__FILE__) . '/../vendor/simpletest/unit_tester.php';
		require_once dirname(__FILE__) . '/../vendor/simpletest/reporter.php';
		require_once dirname(__FILE__) . '/../test/cobweb_core_testcase.class.php';
		require_once dirname(__FILE__) . '/../test/cobweb_test_suite.class.php';
	}
	
	
	public function execute() {
		
		// $reporter = php_sapi_name() == 'cli' ? new TextReporter() : new HtmlReporter();
		// 		$test = new TestSuite(sprintf('Cobweb %s Core Tests', Cobweb::VERSION));
		// 		
		// 		$test_directory = new DirectoryIterator(dirname(__FILE__) . '/../test/');
		// 		foreach ($test_directory as $suite_directory) {
		// 			if ($suite_directory->isDir() && !$suite_directory->isDot()) {
		// 				// $suite = new TestSuite(str_titlecase($file->getFilename()) . ' Test');
		// 				
		// 				$sub_suite = new DirectoryIterator($suite_directory->getPathname());
		// 				foreach ($sub_suite as $test_file)
		// 				
		// 					if ($test_file->isFile() && str_ends_with($test_file->getFilename(), '.suite.php'))
		// 						$test->addTestFile($test_file->getPathname());
		// 				
		// 				
		// 			}
		// 		}
		// 
		// 		$test->run($reporter);
		
	}
}