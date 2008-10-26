<?php

/**
 * Represents the current session
 * 
 * This class is a simple wrapper around the PHP builtin session functionality.
 * If `INSTALLED_MIDDLEWARE' includes `cobweb.session', a session object is 
 * automatically added to the request object.
 * 
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class Session implements ArrayAccess {
	
	public function __construct() {
		$session_parameters 
		session_start();
	}
	
	public function offsetExists($key) {
		return isset($_SESSION[$key]);
	}
	
	public function offsetGet($key) {
		return $_SESSION[$key];
	}
	
	public function offsetSet($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public function offsetUnset($key) {
		unset($_SESSION[$key]);
	}
	
	public function end() {
		return session_destroy();

	}
	
	public function __toArray() {
		return $_SESSION;
	}
}