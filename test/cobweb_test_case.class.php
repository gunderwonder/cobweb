<?php

class CobwebTestCase extends PHPUnit_Framework_TestCase {
	
	private $__output = '';
	
	public function dump() {
		$trace = debug_backtrace();
		$function = $trace[1]['class'] . '::' . $trace[1]['function'] . '()' . "\n";
		$arguments = func_get_args();
		$output = implode("\n", array_map('stringify', $arguments));
		$this->__output .= "\n" . $function . $output . "\n";
	}
	
	public function __destruct() {
		echo $this->__output;
	}
}