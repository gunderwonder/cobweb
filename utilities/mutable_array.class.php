<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Utilities
 */
class MutableArray implements ArrayAccess, IteratorAggregate, Countable {
	
	protected $array;
	
	public function __construct($array = NULL) {
		$this->array = is_null($array) ? array() : $array;
	}
	
	public function get($key, $nullvalue) {
		
		if (isset($this[$key]))
			return $this[$key];
			
		return $nullvalue;
	}
	
	public function offsetExists($key) {
		return array_key_exists($key, $this->array);
	}
	
	public function offsetGet($key) {
		return $this->array[$key];
	}
	
	public function offsetSet($key, $value) {
		$this->array[$key] = $value;
	}
	
	public function offsetUnset($key) {
		unset($this->array[$key]);
	}
	
	public function toArray() {
		return $this->array;
	}
	
	public function keys() {
		return array_keys($this->array);
	}
	
	public function values() {
		return array_values($this->array);
	}
	
	public function getIterator() {
		return new ArrayIterator($this->array);
	}
	
	public function count() {
		return count($this->array);
	}
	
}