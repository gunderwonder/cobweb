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
class test_utf8_strlen extends UnitTestCase {

    function test_utf8_strlen() {
        $this->UnitTestCase('utf8_strlen()');
    }
    
    function testUtf8() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEqual(utf8_strlen($str),20);
    }
    
    function testUtf8Invalid() {
        $str = "Iñtërnâtiôn\xe9àlizætiøn";
        $this->assertEqual(utf8_strlen($str),20);
    }
    
    function testAscii() {
        $str = 'ABC 123';
        $this->assertEqual(utf8_strlen($str),7);
    }
    
    function testEmptyStr() {
        $str = '';
        $this->assertEqual(utf8_strlen($str),0);
    }
    
    
}

//--------------------------------------------------------------------
/**
* @package utf8
* @subpackage Tests
*/
if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new test_utf8_strlen();
    $reporter = & getTestReporter();
    $test->run($reporter);
}
