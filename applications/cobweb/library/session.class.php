<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
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
class Session implements SessionStorage {
	
	public function __construct() {
		ini_set('session.use_only_cookies', 1);
		$session_parameters = session_get_cookie_params();
		session_set_cookie_params(
			$session_parameters['lifetime'],
			$session_parameters['path'],
			$session_parameters['domain'],
			$session_parameters['secure'],
			true // session cookies are HTTP Only
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
		session_set_cookie_params(0);
		$this->regenerate();
		return session_destroy();
	}
	
	public function flush() {
	    session_unset();
	}
	
	public function regenerate() {
		session_regenerate_id();
	}
	
	public function expire($expiry) {
		session_set_cookie_params($expiry);
		$this->regenerate();
	}
	
	public function get($key, $default = NULL) {
		if (!isset($this[$key]) && !is_null($default))
			return $default;
		return $this[$key];
	}
	
	public function __get($key) {
		return $this[$key];
	}
	
	public function __set($key, $value) {
		return $this[$key] = $value;
	}
	
	public function __isset($key) {
		return isset($this[$key]);
	}
	
	public function __toArray() {
		return $_SESSION;
	}
}