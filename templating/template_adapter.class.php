<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * A simple adapter mechanism for templating engines.
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Templating
 */
abstract class TemplateAdapter implements ArrayAccess {
	
	protected $bindings;
	
	public function __construct(array $bindings = NULL) {
		$this->bindings = array();
	}
	
	public function bindings() {
		return $this->bindings;
	}
	
	public function bind(array $bindings) {
		$this->bindings = array_merge($this->bindings, $bindings);
		return $this;
	}
	
	// ARRAY ACCESS IMPLEMENTATION
	
	public function offsetExists($key) {
		return array_key_exists($key, $this->bindings);
	}
	
	public function offsetGet($key) {
		return $this->bindings[$key];
	}
	
	public function offsetSet($key, $value) {
		$this->bindings[$key] = $value;
	}
	
	public function offsetUnset($key) {
		unset($this->bindings[$key]);
	}
	
	abstract public function renderFile($filename);
	
}