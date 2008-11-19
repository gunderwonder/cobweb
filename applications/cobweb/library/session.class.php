<?php
/**
 * @version $Id$
 */

/**
 * Represents the current session
 * 
 * This class is a simple wrapper around the PHP builtin session functionality.
 * If <var>INSTALLED_MIDDLEWARE</var> includes <var>cobweb.session</var>, a session 
 * object is automatically added to the request object.
 * 
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class Session implements ArrayAccess {
	
	public function __construct() {
		$session_parameters = session_get_cookie_params();
		session_set_cookie_params(
			$session_parameters['lifetime'],
			$session_parameters['path'],
			$session_parameters['domain'],
			$session_parameters['secure'],
			true
		);
		
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
	
	public function get($key, $default = NULL) {
		if (!isset($this[$key]) && !is_null($default))
			return $default;
		return $this[$key];
	}
	
	public function __toArray() {
		return $_SESSION;
	}
}