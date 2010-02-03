<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Dispatch
 */
class IncludeURLConfigurationAction implements Action {
	
	protected $application_name, $file, $rules;
	
	public function __construct($label, array $options = NULL) {
		$this->label = $label;
		if (count(@list($this->application_name, $this->file) = explode('.', $this->label)) != 2)
			throw new CobwebConfigurationException('Invalid URL configuration label');
		
		if (!in_array($this->application_name, Cobweb::get('INSTALLED_APPLICATIONS')))
			throw new CobwebConfigurationException(
				"'{$this->application_name}' is not in your 'INSTALLED_APPLICATIONS'.");
		
		$applications = Cobweb::instance()->applicationManager()->applications();
		$this->application = $applications[$this->application_name];
		
		$this->options = is_null($options) ? array() : $options;
		$this->rules = NULL;
	}
	
	public function invoke(array $arguments = NULL) {
		
	}
	
	protected function path() {
		return "{$this->application->path()}/settings/{$this->file}.conf.php";
	}
	
	public function hasAnnotation($annotation) {
		return false;
	}
	
	public function annotation($annotation) {
		return NULL;
	}
	
	public function allAnnotations() {
		return array();
	}
	
	public function rules() {
		if (!is_null($this->rules))
			return $this->rules;
		
		$urls_path = $this->path();
		if (file_exists($urls_path)) {
			$this->rules = require $urls_path;
			return $this->rules;
		}
		
		throw new CobwebConfigurationException(
			"No URL configuration file found for {$this->label}");
	}
	
	public function options() {
		return $this->options;
	}
	

	public function name() {
		// XXX: should throw exception here
		return '';
	}
	
}