<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Logging
 */
class Logger implements IteratorAggregate {
	
	/** @var array */
	protected $logs;
	
	/** @var string */
	protected $name;
	
	/** @var string */
	protected $log_level;
	
	const DEBUG = 1;
	const INFO = 2;
	const WARNING = 4;
	const ERROR = 8;
	
	/**
	 * Creates a logger with the specified (optional) name.
	 * 
	 * @param string $name the name of this logger
	 */
	public function __construct($name = '', $log_level = self::DEBUG) {
		$this->name = $name;
		$this->logs = array();
		$this->log_level = $log_level;
		
		$this->LOG_LEVELS = array(
			'DEBUG' => self::DEBUG,
			'INFO' => self::INFO,
			'WARNING' => self::WARNING,
			'ERROR' => self::ERROR,
		);
	}
	
	/**
	 * Add a log message to this logger using the specified function
	 * (one of 'log', 'warn', 'info', 'assert')
	 * 
	 */
	private function append($function, $things) {
		// if ($this->LOG_LEVELS[$function] & $this->log_level)
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