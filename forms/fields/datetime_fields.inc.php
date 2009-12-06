<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class DatetimeField extends FormField {
	protected $INPUT_FORMATS = array(
		'%Y-%m-%d %H:%M:%S',
		'%Y-%m-%d %H:%M',
		'%Y-%m-%d',
		'%m/%d/%Y %H:%M:%S',
		'%m/%d/%Y %H:%M',
		'%m/%d/%Y',
		'%m/%d/%y %H:%M:%S',
		'%m/%d/%y %H:%M',
		'%m/%d/%y'
	);
	
	protected $input_formats = array();
	
	public function __construct(array $properties = array()) {
		parent::__construct($properties);
		$this->input_formats = $this->properties->get('input_formats', $this->INPUT_FORMATS);
	}
	
	public function clean($value) {
		$value = parent::clean($value);
		if (!$value) return $value;
		
		if ($value instanceof DateTime)
			return $value;
		foreach ($this->input_formats as $format)
			if (($date = $this->parseDate($value, $format)) !== false)
				return $date;
		
		throw new FormValidationException($this->error_messages['invalid']);
	}
	
	protected function parseDate($value, $format) {
		if (($date_info = strptime($value, $format)) === false || $date_info['unparsed'])
			return false;
		return $this->createDate($date_info);
	}
	
	protected function createDate(array $date_info) {
		return CWDateTime::create(
			$date_info['tm_year'] + 1900, 
			$date_info['tm_mon'] + 1, 
			$date_info['tm_mday'],
			$date_info['tm_hour'],
			$date_info['tm_min'],
			$date_info['tm_sec']
		);
	}
	
	protected function defaultProperties() {
		return array(
			'error_messages' => array('invalid' => __('Enter a valid date/time.'))
		);
	}
}

class TimeField extends DatetimeField {
	
	protected $INPUT_FORMATS = array('%H:%M:%S', '%H:%M');

	protected function createDate(array $date_info) {
		return array(
			$date_info['tm_hour'],
			$date_info['tm_min'],
			$date_info['tm_sec']
		);
	}
	
	protected function defaultProperties() {
		return array(
			'error_messages' => array('invalid' => __('Enter a valid time.'))
		);
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class DateField extends DatetimeField {
	
	protected $INPUT_FORMATS = array(
    	'%Y-%m-%d', '%m/%d/%Y', '%m/%d/%y',
 		'%m.%d.%y', '%m.%d.%Y', '%b %d %Y', '%b %d, %Y',
    	'%d %b %Y', '%d %b, %Y', '%B %d %Y', 
		'%B %d, %Y', '%d %B %Y', '%d %B, %Y',
	);
	
	protected $input_formats = array();
	
	public function __construct(array $properties = array()) {
		parent::__construct($properties);
		$this->input_formats = $this->properties->get('input_formats', $this->INPUT_FORMATS);	
	}
	
	protected function createDate(array $date_info) {
		return CWDateTime::create(
			$date_info['tm_year'] + 1900, 
			$date_info['tm_mon'] + 1, 
			$date_info['tm_mday']
		);
	}
	
	protected function defaultProperties() {
		return array(
			'error_messages' => array('invalid' => __('Enter a valid date.'))
		);
	}
}

