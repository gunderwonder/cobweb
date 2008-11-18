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
class Time extends DateTime {
	
	const DATE_SQL = 'Y-m-d H:i:s';

	
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
		
		return $t_1 === $t_2 ? 0 : 
		       $t_1 >   $t_2 ? 1 : 
		       -1;
		// 
		// if ($t_1 === $t_2)
		// 	return 0;
		// if ($t_1 > $t_2)
		// 	return 1;
		// if ($t_1 < $t_2)
		// 	return -1;
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
		return intval($this->format('U'));
	}
	
	public function modify($modification) {
    	parent::modify($modification);
		return $this;
	}
	
	public function subtract(DateTime $t_2) {
		return new TimeDelta($this->timestamp() - $t_2->format('U'));
	}
	
	public function add(TimeDelta $d) {		
		$modifier = $d->difference() < 0 ? '-' : '+';
		return $this->copy()->modify("{$modifier}{$d->difference()} seconds");
	}

	public function __toString() {
		return $this->format(self::DATE_SQL);
	}
	
	public function __toSQL() {
		return $this->__toString();
	}
	
	public static function sorter(Time $t_1, Time $t_2) {
		return $t_1->compare($t_2);
	}
	
	public function copy() {
		$clone = clone $this;
		return $clone;
	}
}

class TimeDelta {
	private $timestamp;

	
	public function __construct($timestamp) {
		$this->timestamp = $timestamp;
	}
	
	public function difference() {
		return $this->timestamp;
	}
	
	public function __toString() {
		return strval($this->difference());
	}
	
}