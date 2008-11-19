<?php

class ApplicationManager {
	
	protected 
		$loaded_applications,
		$dispatcher,
		$request;
	
	public function __construct(Dispatcher $dispatcher, Request $request) {
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->loaded_applications = array();
		
		foreach (Cobweb::get('INSTALLED_APPLICATIONS') as $application_name)
			$this->load($application_name);
	}
	
	public function load($application_name) {
		
		if (isset($this->loaded_applications[$application_name]))
			return $this->loaded_applications[$application_name];
		
		if (!in_array($application_name, Cobweb::get('INSTALLED_APPLICATIONS')))
			throw new CobwebConfigurationException(
				"'{$application_name}' is not in your 'INSTALLED_APPLICATIONS'.");
		
		foreach (Cobweb::get('APPLICATIONS_PATH') as $path) {
			$path = "{$path}/{$application_name}";
			if (is_dir($path)) {
				$this->loaded_applications[$application_name] = 
					new Application($this->dispatcher, 
						            $this->request,
						            $application_name,
						            $path);
					
				Cobweb::info('Loaded application %o', $application_name);
				return $this->loaded_applications[$application_name];
			}
		}
		throw new CobwebConfigurationException(
			"Could not load application {$application_name}");
	}
	
	public function loadedApplications() {
		return $this->loaded_applications;
	}
	
	
}