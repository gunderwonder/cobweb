<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class DateField extends FormField {
	
	protected $INPUT_FORMATS = array(
    	'%Y-%m-%d', '%m/%d/%Y', '%m/%d/%y',
    	'%b %d %Y', '%b %d, %Y',
    	'%d %b %Y', '%d %b, %Y',
    	'%B %d %Y', '%B %d, %Y',
    	'%d %B %Y', '%d %B, %Y'
	);
	
	protected $input_formats = array();
	
	
	public function __construct(array $properties = array()) {
		parent::__construct($properties);
		$this->input_formats = $this->properties->get('input_formats', $this->INPUT_FORMATS);	
	}
	
	public function clean($value) {
		parent::clean($value);
		if ($value instanceof DateTime)
			return $value;
		foreach ($this->input_formats as $format)
			if (($date = $this->parseDate($value, $format)) !== false)
				return $date;
		
		throw new FormValidationException($this->error_message['invalid']);
	}
	
	protected function parseDate($value, $format) {
		if (($date_info = strptime($value, $format)) === false || $date_info['unparsed'])
			return false;
		return CWDateTime::create(
			$date_info['tm_year'] + 1900, 
			$date_info['tm_mon'] + 1, 
			$date_info['tm_mday']
		);
	}
}