<?php

class Logger implements IteratorAggregate {
	
	/** @var array */
	protected $logs;
	
	/** @var string */
	protected $name;
	
	/**
	 * Creates a logger with the specified (optional) name.
	 * 
	 * @param string $name the name of this logger
	 */
	public function __construct($name = '') {
		$this->name = $name;
		$this->logs = array();
	}
	
	/**
	 * Add a log message to this logger using the specified function
	 * (one of 'log', 'warn', 'info', 'assert')
	 * 
	 */
	private function append($function, $things) {
		$this->logs[] = array($function, $things);
	}
	
	public function isEmpty() {
		return count($this->logs) == 0;
	}
	
	public function log($things) {
		$this->append('log', $things);
		
	}
	
	public function warn($things) {
		$this->append('warn', $things);
	}
	
	public function info($things) {
		$this->append('info', $things);
	}
	
	public function assert($things) {
		$this->append('assert', $things);

	}
	
	/**
	 * Logs an error
	 * 
	 * @param array $things the error to log
	 */
	public function error($things) {
		$this->append('error', $things);

	}
	
	public function name() {
		return $this->name;
	}
	
	public function getIterator() {
		return new ArrayIterator($this->logs);
	}
	
}

?>