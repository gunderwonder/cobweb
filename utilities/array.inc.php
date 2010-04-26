<?php


class CWArray extends ArrayObject {
	
	public function __construct($traversable = array(), $iterator_class = 'ArrayIterator') {
		
		parent::__construct(self::coerce($traversable), 0, $iterator_class);
	}
	
	public static function coerce($traversable) {
		if (is_array($traversable))
			return $traversable;
		else if ($traversable instanceof ArrayObject)
			return $traversable->getArrayCopy();
		else if ($traversable instanceof Iterator)
			return iterator_to_array($traverable);
		else if ($traversable instanceof IteratorAggregate)
			return iterator_to_array($traversable->getIterator());
		return (array)$traverable;
	}
	
	public static function create($traversable = array(), $iterator_class = 'ArrayIterator') {
		return new CWArray($traversable, $iterator_class);
	}
	
	public function isEmpty() {
		return $this->count() == 0;
	}
	
	public function contains($value) {
		return in_array($value, $this->toArray());
	}
	
	public function first() {
		if ($this->isEmpty()) throw new OutOfBoundsException('CWArray is empty');
		$keys = $this->keys();
		return $this[$keys[0]];
	}
	
	public function last() {
		if ($this->isEmpty()) throw new OutOfBoundsException('CWArray is empty');
		$keys = $this->keys();
		return $this[end($keys)];
	}
	
	public function concatenate($traversable) {
		foreach (self::coerce($traversable) as $value)
			$this->append($value);
		return $this;
	}
	
	public function merge($traversable) {
		$this->exchangeArray(array_merge($this->toArray(), self::coerce($traversable)));
		return $this;
	}
	
	public function get($key, $default_value = NULL) {
		return !isset($this[$key]) ? $default_value : $this[$key];
	}
	
	public function toArray() {
		return $this->getArrayCopy();
	}
	
	public function keys() {
		return array_keys($this->toArray());
	}
	
	public function values() {
		return array_keys($this->toArray());
	}
	
	public function map($callable) {
		$results = array();
		foreach ($this as $k => $v)
			$results[$k] = call_user_func($callable, $v, $k);
		return $results;
	}
	
	public function detect($callable) {
		foreach ($this as $k => $v)
			if (call_user_func($callable, $v, $k))
				return $v;
		return false;
	}
	
	public function select($callable) {
		$result = array();
		foreach ($this as $k => $v)
			if (call_user_func($callable, $v, $k))
				$result[$k] = $v;
		return $result;
	}
	
	public function all($callable = NULL) {
		$callable = $callable ? $callable : 'identity_function';
		foreach ($this as $k => $v)
			if (!call_user_func($callable, $v, $k))
				return false;
		return true;
	}
	
	public function any($callable = NULL) {
		$callable = $callable ? $callable : 'identity_function';
		foreach ($this as $k => $v)
			if (call_user_func($callable, $v, $k))
				return true;
		return false;
	}
	
	public function filter($callable = NULL) {
		return new CWArray(array_filter($this->toArray(), $callable));
	}
	
	public function inject($accumulator, $callable) {
		foreach ($this as $k => $v)
			$accumulator = call_user_func($callable, $accumulator, $v, $k);
		return $accumulator;
	}
	
	public function zip() {
		$arrays = func_get_args();
		if (empty($arrays))
			return array();
		$last = end($arrays);
		$callable = is_callable($last) ? array_pop($arrays) : 'identity_function';
		
		$result = array();
		foreach ($this as $k => $v) {
			$values = array($this[$k]);
			foreach ($arrays as $array)
				$values[] = isset($array[$k]) ? $array[$k] : NULL;
			$result[$k] = call_user_func($callable, $values);
		}
		return $result;
	}

	
	public function sort($comparator = NULL, $inline = true) {	
		if ($inline) {
			if ($comparator)
				$this->uasort($comparator);
			else
				$this->asort();
			return $this;
		}
		
		$array = $this->toArray();
		if ($comparator)
			uasort($array, $comparator);
		else
			asort($array);
			
		$class_name = get_class($this);
		return new $class_name($array);
	}
	
	
	public function sortKeys($comparator = NULL, $inline = true) {	
		if ($inline) {
			if ($comparator)
				$this->uksort($comparator);
			else
				$this->ksort();
			return $this;
		}
		$array = $this->toArray();
		if ($comparator)
			uksort($array, $comparator);
		else
			ksort($array);
		
		$class_name = get_class($this);
		return new $class_name($array);
	}
	
	public function immutable() {
		return new ImmutableCWArray($this);
	}
}

class ImmutableCWArray extends CWArray {
	
	protected $error_message = 'CWArray instance is immutable';
	
	public function __construct($array = array(), $error_message = NULL, $iterator_class = 'ArrayIterator') {
		$this->error_message = $error_message ? $error_message : $this->error_message;
		
		parent::__construct($array, $iterator_class);
	}
	
	public function notice() { throw new Exception($this->error_message); }
	public function append($v) { $this->notice(); }
	public function asort() { $this->notice(); }
	public function count() { $this->notice(); }
	public function exchangeArray($a) { $this->notice(); }
	public function ksort() { $this->notice(); }
	public function natcasesort() { $this->notice(); }
	public function natsort() { $this->notice(); }
	public function offsetSet($k, $v) { $this->notice(); }
	public function offsetUnset($k) { $this->notice(); }
	public function uasort($c) { $this->notice(); }
	public function uksort($c) { $this->notice(); }
	public function concatenate($a) { $this->notice(); }
	public function merge($a) { $this->notice(); }
}

/**
 * @deprecated
 */
class MutableArray extends CWArray { }

/**
 * @deprecated
 */
class ImmutableArray extends ImmutableCWArray { }

