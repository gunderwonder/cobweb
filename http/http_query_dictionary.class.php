<?php

class HTTPQueryDictionary extends ImmutableArray {
	
	public function get($key, $nullvalue = NULL) {
		if (!empty($this[$key]))
			return $this[$key];
			
		return $nullvalue; 
	}
	
	public function query() {
		$query_string = '';
		
		$first = true;
		foreach ($this as $parameter => $value) {
			$parameter = rawurlencode($parameter);
			$value = rawurlencode($value);
			
			$query_string .= ($first ? '' : '&') . "{$parameter}";
			$first = false;
			if (!empty($value))
				$query_string .= "={$value}";
		}
			
		return $query_string;
	}
	
	public function __toString() {
		return $this->query();
	}
}