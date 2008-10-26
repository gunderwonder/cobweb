<?php

class IncludeURLConfigurationAction implements Action {
	
	protected $application_name, $file;
	
	public function __construct($label, array $options = NULL) {
		$this->label = $label;
		if (count(list($this->application_name, $this->file) = explode('.', $this->label)) != 2)
			throw new CobwebConfigurationException('Invalid URL configuration label');
		if (!in_array($this->application_name, Cobweb::get('INSTALLED_APPLICATIONS')))
			throw new CobwebConfigurationException(
				"'{$application_name}' is not in your 'INSTALLED_APPLICATIONS'.");
		
		$this->options = is_null($options) ? array() : $options;
	}
	
	public function invoke(array $arguments = NULL) {
		
	}
	
	protected function path() {
		return "/{$this->application_name}/settings/{$this->file}.conf.php";
	}
	
	public function rules() {
		foreach (Cobweb::get('APPLICATIONS_PATH') as $path) {
			$urls_path = $path . $this->path();
			if (file_exists($urls_path)) {		
				require_once $urls_path;
				return Router::map();

			}
		}
		throw new CobwebConfigurationException(
			"No URL configuration file found for {$this->label}");
	}
	
	public function options() {
		return $this->options;
	}
	
}