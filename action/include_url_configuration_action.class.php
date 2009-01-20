<?php

class IncludeURLConfigurationAction implements Action {
	
	protected $application_name, $file, $rules;
	
	public function __construct($label, array $options = NULL) {
		$this->label = $label;
		if (count(list($this->application_name, $this->file) = explode('.', $this->label)) != 2)
			throw new CobwebConfigurationException('Invalid URL configuration label');
		if (!in_array($this->application_name, Cobweb::get('INSTALLED_APPLICATIONS')))
			throw new CobwebConfigurationException(
				"'{$application_name}' is not in your 'INSTALLED_APPLICATIONS'.");
		
		$this->options = is_null($options) ? array() : $options;
		$this->rules = NULL;
	}
	
	public function invoke(array $arguments = NULL) {
		
	}
	
	protected function path() {
		return "/{$this->application_name}/settings/{$this->file}.conf.php";
	}
	
	public function hasAnnotation($annotation) {
		return false;
	}
	
	public function annotation($annotation) {
		return NULL;
	}
	
	public function rules() {
		if (!is_null($this->rules))
			return $this->rules;
		
		foreach (Cobweb::get('APPLICATIONS_PATH') as $path) {
			
			$urls_path = $path . $this->path();
			if (file_exists($urls_path)) {
				$this->rules = require_once $urls_path;
				return $this->rules;
			}
				

		}
		throw new CobwebConfigurationException(
			"No URL configuration file found for {$this->label}");
	}
	
	public function options() {
		return $this->options;
	}
	
	// public function setResolver(Resolver $resolver) {
	// 	$this->resolver = $resolver;
	// }
	// 
	// public function resolver() {
	// 	return $this->resolver;
	// }
	
	public function name() {
		// XXX: should throw exception here
		return '';
	}
	
}