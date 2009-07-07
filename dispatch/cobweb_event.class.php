<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Represents an event in the Cobweb event notification system
 * 
 * @see Dispatcher
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Dispatch
 * @version    0.2
 */
class CobwebEvent {
	
	/** @var string */
	private $event_name;
	
	/** @var array */
	public $memo;
	
	/** @var bool */
	private $is_stopped;
	
	/**
	 * Creates an event with the specified label and memo.
	 * 
	 * @param string $event_name  the name of this event
	 * @param array  $memo        the memo to pass to the 
	 */
	public function __construct($event_name, array $memo) {
		$this->event_name = $event_name;
		$this->memo = new ImmutableArray($memo);
		
		$this->is_stopped = false;
		
		$this->return_values = new ImmutableArray(array());
	}

	/**
	 * Returns the name of this event
	 * 
	 * @return string the event name
	 */	
	public function name() {
		return $this->event_name;
	}
	
	/**
	 * Returns the memo of this event.
	 * 
     * @see    CobwebEvent::__get
	 * @return ImmutableArray this event's memo
	 */
	public function memo() {
		return $this->memo;
	}
	
	/**
	 * Adds a return value to this event
	 * 
	 * @param mixed $value return value
	 */
	public function tell($value) {
		$this->return_values[$key] = $value;
	}
	
	/**
	 * Return whatever values the event listeners added to this event
	 * 
	 * @return ImmutableArray return values
	 */
	public function returnValues() {
		return $this->return_values;
	}
	
	/**
	 * Stops propagation of this event
	 */
	public function stop() {
		$this->is_stopped = true;
	}
	
	/**
	 * Returns true if the event has stopped propagating, false otherwise.
	 * 
	 * @return boolean if the event is stopped or not
	 */
	public function isStopped() {
		return $this->is_stopped;
	}
	
	/**
	 * Provides access to the event's memo values
	 * 
	 * @param  string $key name of the variable to retrieve
	 * @return mixed       memo's value 
	 */
	public function __get($key) {
		if (isset($this->memo[$key]))
			return $this->memo[$key];
		return NULL;
	}
}