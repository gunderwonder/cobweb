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
class TextField extends FormField {
	
	protected $min_length = NULL;
	protected $max_length = NULL;
	
	protected function initialize() {
		$this->min_length = $this->properties->get('min_length', NULL);
		$this->max_length = $this->properties->get('max_length', NULL);
	}
	
	protected function defaultProperties() {
		return array('error_messages' => array(
			'max_length' => __('Ensure this value has at most %1$d characters (it has %2$d).'),
        	'min_length' => __('Ensure this value has at least %1$d characters (it has %2$d).'),
		));
	}
	
	public function clean($value) {
		parent::clean($value);
		
		// handle arrays, objects without __toString()
		try {
			if (is_array($value)) trigger_error();
			$value = (string)$value;
		} catch (ErrorException $e) {
			throw new FormValidationException($this->error_messages['invalid']);
		}
		
		if (in_array($value, array(NULL, '')))
            return '';
		
		$length = utf8_strlen($value);
		if (!is_null($this->max_length) && $length > $this->max_length)
			throw new FormValidationException(
				$this->message('max_length', $this->max_length, $length)
			);
		if (!is_null($this->min_length) && $length < $this->min_length)
			throw new FormValidationException(
				$this->message('min_length', $this->min_length, $length)
			);
		return $value;
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class RegexField extends TextField {
	
	protected $regex = NULL;
	
	public function __construct($regex, array $properties = array()) {
		$this->regex = $regex;
		parent::__construct($properties);
	}
	
	public function clean($value) {
		$value = parent::clean($value);
		if ($value == '') return $value;
		
		if (!is_array($this->regex)) $this->regex = array($this->regex);
		foreach ($this->regex as $pattern)
			if (preg_match($pattern, $value))
				return $value;
		throw new FormValidationException(sprintf($this->error_messages['invalid']));
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class SlugField extends RegexField {
	
	const SLUG_RE = '/^[-\w]+$/';
	
	protected $slugify_value = false;
	
	public function __construct(array $properties = array()) {
		parent::__construct(self::SLUG_RE, $properties);
	}
	
	public function initialize() {
		$this->slugify_value = $this->properties->get('slugify_value', false);
	}
	
	public function clean($value) {
		try {
			$value = parent::clean($value);
		} catch (FormValidationException $e) {
			if ($this->slugify_value)
				return parent::clean(str_slugify($value));
			throw $e;
		}
		return $value;
	}
	
	protected function defaultProperties() {
		return array(
			'error_messages' => array(
				'invalid' => __(
					"Enter a valid 'slug' consisting of letters, " .
					"numbers, uderscores or hyphens."
				)
			)
		);
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class CustomField extends FormField {
	protected $cleaner = NULL;
	
	public function __construct($cleaner, array $protected = array()) {
		$this->cleaner = $cleaner;
		if (!is_callable($cleaner))
			throw new InvalidArgumentException('Expected callable, got ' . gettype($cleaner));
	}
	
	public function clean($value) {
		return call_user_func($this->cleaner, $value);
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class CustomTextField extends TextField {
	protected $cleaner = NULL;
	
	public function __construct($cleaner, array $properties = array()) {
		$this->cleaner = $cleaner;
		if (!is_callable($cleaner))
			throw new InvalidArgumentException('Expected callable, got ' . gettype($cleaner));
		parent::__construct($properties);
	}
	
	public function clean($value) {
		return call_user_func($this->cleaner, parent::clean($value));
	}
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class EmailField extends TextField {
	
	private static $included_validator = false;
	protected $check_mx = false;
	
	public function initialize() {
		if (!self::$included_validator)
			require_once COBWEB_DIRECTORY . '/vendor/rfc3696.php';
		self::$included_validator = true;
		$this->check_mx = $this->properties->get('check_mx', false);
	}
	
	public function clean($value) {
		$value = parent::clean($value);
		if (!@is_rfc3696_valid_email_address($value))
			throw new FormValidationException($this->error_messages['invalid']);
		if ($this->check_mx) {			
			if (!function_exists('checkdnsrr'))
				Cobweb::warn('Skipping MX record check, checkdnsrr() is undefined');
			else {
				list($user, $host) = explode('@', $value);
				if (!checkdnsrr($host, 'MX'))
					throw new FormValidationException($this->error_messages['mx_record']);
			}
		}
		return $value;
	}
	
	protected function defaultProperties() {
		return array(
			'error_messages' => array(
				'invalid' => __('Enter a valid e-mail address.'),
				'mx_record' => __('Unknown mail host. Enter a valid e-mail address.'), // ahem.
			)
		);
	}
}