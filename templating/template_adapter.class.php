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
	
	const INTERPOLATE_STRING = 'string';
	const INTERPOLATE_FILE = 'file';
	const INTERPOLATE_RESOURCE = 'resource';
	
	protected $bindings;
	
	public function __construct(array $bindings = NULL) {
		$this->bindings = array();
		$this->initialize();
	}
	
	protected function initialize() { }
	
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
	
	
	abstract public function interpolate($template, $interpolation_mode = self::INTERPOLATE_FILE);
	
	/**
	 * @deprecated use `TemplateAdapter::interpolate()` instead
	 */
	public function renderFile($filename) {
		return $this->interpolate($filename, self::INTERPOLATE_FILE);
	}
	
	
}