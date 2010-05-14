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
class NumericField extends FormField {
	
	protected $min_value = NULL;
	protected $max_value = NULL;
	
	public function __construct(array $properties = array()) {
		if (isset($properties['min_value']))
			$this->min_value = $properties['min_value'];
		if (isset($properties['max_value']))
			$this->max_value = $properties['max_value'];
			
		parent::__construct($properties);
	}
	
	protected function defaultProperties() {
		return array('error_messages' => array(
			'invalid' => __('Enter a valid number.'),
			'max_value' => __('Ensure this value is less than or equal to %1$s.'),
			'min_value' => __('Ensure this value is greater than or equal to %1$s.'),
		));
	}
	
	public function clean($value) {
		parent::clean($value);
		$number = $this->normalizeNumber($value);

		if (!is_null($this->max_value) && $number > $this->max_value)
			throw new FormValidationException(
				$this->message('max_value', $this->max_value, $number)
			);
		if (!is_null($this->min_value) && $number < $this->min_value)
			throw new FormValidationException(
				$this->message('min_value', $this->min_value, $number)
			);
		return $number;
	}
	
	protected function normalizeNumber($value) {
		if (is_int(($int_value = filter_var($value, FILTER_VALIDATE_INT))))
			return $int_value;
		if (is_float(($float_value = filter_var($value, FILTER_VALIDATE_FLOAT))))
			return $float_value;
		throw new FormValidationException($this->message('invalid'));
	}
}

class IntegerField extends NumericField {
	
	public function clean($value) {
		if (!is_int($number = parent::clean($value)))
			throw new FormValidationException($this->message('invalid'));
		return $number;
	}
	
	protected function defaultProperties() {
		return array_merge(parent::defaultProperties(),
			array('error_messages' => array(
				'invalid' => __('Enter a whole number.')
			),
		));
	}
}

class FloatField extends IntegerField {
	
	public function clean($value) {
		$value = parent::clean($value);
		$value = filter_var($value, FILTER_VALIDATE_FLOAT);
		if ($value === false)
			throw new FormValidationException($this->message('invalid'));
		return $value;
	}
	
	protected function defaultProperties() {
		return array_merge(parent::defaultProperties(),
			array('error_messages' => array(
				'invalid' => __('Enter a valid floating point number.')
			),
		));
	}
}

// TODO
// class DecimalField extends NumericField {
// 	
// }