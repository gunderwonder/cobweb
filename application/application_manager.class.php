<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Application
 */
class ApplicationManager {
	
	protected 
		$loaded_applications,
		$dispatcher,
		$request;
	
	public function __construct(Dispatcher $dispatcher, Request $request) {
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->loaded_applications = array();
		
		$installed_applications = Cobweb::get('INSTALLED_APPLICATIONS');
		if (!in_array('cobweb', $installed_applications))
			array_unshift($installed_applications, 'cobweb');
		Cobweb::set('INSTALLED_APPLICATIONS', $installed_applications);
		
		foreach ($installed_applications as $application_name)
			$this->load($application_name);
			
		Cobweb::info('Loaded applications %o', Cobweb::get('INSTALLED_APPLICATIONS'));
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