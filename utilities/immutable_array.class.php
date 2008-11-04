<?php
/**
 * @version $Id$
 */

/**
 * @package    Cobweb
 * @subpackage Utilities
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision
 */
class ImmutableArray implements ArrayAccess, IteratorAggregate, Countable {
	
	protected $array;
	
	public function __construct($array) {
		$this->array = $array;
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
		throw new CobwebException('Array is immutable');
	}
	
	public function offsetUnset($key) {
		throw new CobwebException('Array is immutable');
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