<?php
/**
* @version $Id$
* @package utf8
* @subpackage Tests
*/

//--------------------------------------------------------------------
/**
* Includes
* @package utf8
* @subpackage Tests
*/
require_once(dirname(__FILE__).'/../config.php');

//--------------------------------------------------------------------
/**
* @package utf8
* @subpackage Tests
*/
class test_utf8_strtolower extends UnitTestCase {

    function test_utf8_strtolower() {
        $this->UnitTestCase('utf8_strtolower()');
    }
    
    function testLower() {
        $str = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
        $lower = 'iñtërnâtiônàlizætiøn';
        $this->assertEqual(utf8_strtolower($str),$lower);
    }
    
    function testEmptyString() {
        $str = '';
        $lower = '';
        $this->assertEqual(utf8_strtolower($str),$lower);
    }
}

//--------------------------------------------------------------------
/**
* @package utf8
* @subpackage Tests
*/
if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new test_utf8_strtolower();
    $reporter = & getTestReporter();
    $test->run($reporter);
}
