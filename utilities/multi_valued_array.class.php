<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/** 
 * Implements a multi-valued array
 * 
 * The keys in a {@link MultiValuedArray} values may have multiple values. This 
 * is useful for situations in which one unique identifyer is associated with
 * more than one value such as HTTP headers, GET parameters in URLs and so on.
 * 
 * Iterating over a {@link MultiValuedArray} using foreach yields all the values
 * in the dictionary:
 * 
 * <code>
 * 	$foo = new MultiValuedArray();
 *  $foo['bar'] = 1;
 * 	$foo['bar'] = 2; // does not overwrite the value 1
 * 
 * 	$foo['baz'] = 3;
 * 
 * 	foreach($foo as $k => $v)
 * 		echo "{$k} => {$v}\n";
 * 
 * 	echo "\$foo contains {$foo->count()} elements";
 * 
 * 	// bar => 1
 * 	// bar => 2
 * 	// baz => 3
 *	// $foo contains 3 elements
 * 
 * </code>
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Utilities
 */
class MultiValuedArray implements ArrayAccess, Iterator, Countable {
	
	private $array;
	private $key;
	private $key_offset;
	private $offset;
	
	public function __construct() {
		$this->array = array();
		$this->offset = 0;
		$this->key = NULL;
	}
	
	public function get($key, $nullvalue = array()) {
		if (!isset($this[$key]))
			return $nullvalue;
		
		return $this->array[$key];
	}
	
	
	// ArrayAccess
	public function replace($key, $value) {
		$this->array[$key] = array($value);
	}
	
	public function offsetSet($key, $value) {
		if (!is_array($this->array[$key]))
			$this->array[$key] = array();
		
		$this->array[$key][] = $value;	
	}
	
	public function offsetGet($key) {
		return $this->array[$key][count($this->array[$key]) - 1];
	}
	
	public function offsetUnset($key) {
		unset($this->array[$key][count($this->array[$key]) - 1]);
	}
	
	public function offsetExists($key) {
		return isset($this->array[$key]);
	}
	
	public function merge(MultiValuedArray $other) {
		foreach ($other as $k => $v) {
			if (isset($this[$k]))
				$this->replace($k, $v);
			else
				$this[$k] = $v;
		}
	}
	
	// Iterator
	public function rewind() {
        reset($this->array);
		$this->key = key($this->array);
		$this->offset = 0;
    }

    public function current() {
		return $this->array[$this->key][$this->offset];
    }

    public function key() {
		return $this->key;
    }

    public function next() {
		$current = $this->array[$this->key]; 
		if (isset($current[$this->offset + 1]))
			$this->offset++;
		else {
			next($this->array);
			$this->key = key($this->array);
			$this->offset = 0;
		}
			
    }

    public function valid() {
		return isset($this->array[$this->key][$this->offset]);
    }

	// Countable
	public function count() {
		$count = 0;
		foreach ($this->array as $a)
			$count += count($a);
		return $count;
	}
	
	
}


