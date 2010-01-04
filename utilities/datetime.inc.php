<?php
/**
 * @version $Id$
 */

/**
 * @package    Cobweb
 * @subpackage Utilities
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 *
 * @see http://laughingmeme.org/2007/02/27/looking-at-php5s-datetime-and-datetimezone/
 */
class CWDateTime extends DateTime {
	
	const DATE_SQL = 'Y-m-d H:i:s';

	public function __construct($time = 'now') {
		if (is_int($time))
			$time = "@{$time}";
		parent::__construct($time);
	}

	public static function create($time = 'now') {
		if ($time instanceof DateTime)
			return self::createfromDateTime($time);
		$arguments = func_get_args();
		if (count($arguments) > 2) {
			$time = new CWDateTime();
			for ($i = 3; $i < 6; $i++)
				if (!isset($arguments[$i])) $arguments[] = 0;
			call_user_func_array(array($time, 'set'), $arguments);
			return $time;
		}
		return new CWDateTime($time);
	}
	
	public static function createfromDateTime(DateTime $dt) {
		if ($dt instanceof CWDateTime)
			return clone $dt;
		return new CWDateTime(intval($dt->format('U')));
	}
	
	public function set($year = NULL, $month = NULL, $day = NULL, 
		                $hours = NULL, $minutes = NULL, $seconds = NULL) {
			
		$year    = is_null($year) ? $this->year : $year;
		$month   = is_null($month) ? $this->month : $month;
		$day     = is_null($day) ? $this->day : $day;

		$hours   = is_null($hours) ? $this->hours : $hours;
		$minutes = is_null($minutes) ? $this->minutes : $minutes;
		$seconds = is_null($seconds) ? $this->seconds : $seconds;		
		
		$this->setDate($year, $month, $day);
		$this->setTime($hours, $minutes, $seconds);

		return $this;
	}
	
	
	public function compare(DateTime $d) {
		$t_1 = $this->timestamp();
		$t_2 = intval($d->format('U'));
		if ($t_1 == $t_2) return 0;
		if ($t_1 > $t_2) return 1;
		return -1;
	}
	
	public function __get($key) {
		switch ($key) {
			case 'year':    return intval($this->format('Y')); break;
			case 'month':   return intval($this->format('n')); break;
			case 'day':     return intval($this->format('j')); break;
			
			case 'hours':   return intval($this->format('H')); break;
			case 'minutes': return intval($this->format('i')); break;
			case 'seconds': return intval($this->format('s')); break;
			
			
			default : return $this->$key;
		}
	}
	
	public function __set($key, $value) {
		switch ($key) {
			case 'year':    $this->set($value, NULL, NULL, NULL, NULL, NULL); break;
			case 'month':   $this->set(NULL, $value, NULL, NULL, NULL, NULL); break;
			case 'day':     $this->set(NULL, NULL, $value, NULL, NULL, NULL); break;
			case 'hours':   $this->set(NULL, NULL, NULL, $value, NULL, NULL); break;
			case 'minutes': $this->set(NULL, NULL, NULL, NULL, $value, NULL); break;
			case 'seconds': $this->set(NULL, NULL, NULL, NULL, NULL, $value); break;
			
			default : return $this->$key = $value;
		}
	 	return $this;
	}
	
	public function timestamp() {
		if (method_exists($this, 'getTimestamp'))
			return $this->getTimestamp();
			
		return intval($this->format('U'));
	}
	
	public function modify($modification) {
    	parent::modify($modification);
		return $this;
	}
	
	public function subtract(DateTime $t_2) {
		return new CWTimeDelta($this, $t_2);
	}
	
	public function add(CWTimeDelta $d) {
		$modifier = $d->difference() < 0 ? '-' : '+';
		return $this->copy()->modify("{$modifier}{$d->difference()} seconds");
	}

	public function __toString() {
		return $this->format(self::DATE_SQL);
	}
	
	public function __toSQL() {
		return $this->__toString();
	}
	
	public static function comparator(DateTime $t_1, DateTime $t_2) {
		return self::createfromDateTime($t_1)->compare($t_2);
	}
	
	public function copy() {
		$clone = clone $this;
		return $clone;
	}
	
	/**
	 * @deprecated
	 */
	public static function sorter(CWDateTime $t_1, CWDateTime $t_2) {
		return self::comparator($t_1, $t_2);
	}
}

class CWTimeDelta {
	private $timestamp;

	public function __construct(DateTime $t_0, DateTime $_1) {
		$this->timestamp = $t_0->format('U') - $t_1->format('U');
	}
	
	public function difference() {
		return $this->timestamp;
	}
	
	public function __toString() {
		return strval($this->difference());
	}
	
}