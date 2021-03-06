<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Configuration
 */
class CobwebConfiguration implements Configurable {
	
	protected $settings;
	
	public function __construct() {
		$this->settings = array();
	}
	
	public function get($key, $default_value = NULL) {
		if (isset($this->settings[$key]))
			return $this->settings[$key];
		if (is_null($default_value))
			throw new CobwebConfigurationException("No setting for key '$key'");
		
		return $default_value;
	}
	
	public function set($key, $value) {
		$this->settings[$key] = $value;
		
		return $this->settings[$key];
	}
	       
	public function configure(array $settings) {
		$this->settings = self::merge($this->settings, $settings);
		
		return $this->settings;
	}
	
	public function load($file = NULL) {
		if (!file_exists($file))
			throw new FileNotFoundException("Settings file '$file' not found");
			
		$settings = require_once $file;
		
		return $settings;
	}
	
	private static function merge($settings, $other_settings) {
		return array_merge($settings, $other_settings);
	}
	
	public function settings() {
		return $this->settings;
	}
	
	
}