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
	
	protected static $cobweb_applications = array();
	
	protected 
		$installed_applications,
		$applications,
		$dispatcher,
		$request;
	
	public function __construct(Dispatcher $dispatcher, Request $request) {
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->applications = array();
		
		
		// `cobweb` application is _always_ installed
		$this->installed_applications = Cobweb::get('INSTALLED_APPLICATIONS');
		if (!in_array('cobweb', $this->installed_applications)) {
			array_unshift($this->installed_applications, 'cobweb');
			Cobweb::set('INSTALLED_APPLICATIONS', $this->installed_applications);
		}
		
		self::$cobweb_applications = array(
			'authentification' => COBWEB_DIRECTORY . '/applications/authentification',
			'cache' => COBWEB_DIRECTORY . '/applications/cache',
			'cobweb' => COBWEB_DIRECTORY . '/applications/cobweb',
			'doctrine' => COBWEB_DIRECTORY . '/applications/doctrine',
			'redirects' => COBWEB_DIRECTORY . '/applications/redirects',
			'sites' => COBWEB_DIRECTORY . '/applications/sites',
		);
		
		// first, create the applications
		foreach ($this->installed_applications as $application_name)
			$this->applications[$application_name] = $this->createApplication($application_name);
			
		Cobweb::info('Loaded applications %o', $this->installed_applications);
	}
	
	protected function createApplication($application_name) {
		
		// if it's loaded...
		if (isset($this->applications[$application_name]))
			return $this->applications[$application_name];
		
		// if it ain't installed...
		if (!in_array($application_name, $this->installed_applications))
			throw new CobwebConfigurationException(
				"'{$application_name}' is not in your 'INSTALLED_APPLICATIONS'.");
		
		// resolve the application path; short-curcuit if it's built-in
		$application_path = NULL;
		if (isset(self::$cobweb_applications[$application_name]))
			$application_path = self::$cobweb_applications[$application_name];
		else {
			foreach (Cobweb::get('APPLICATIONS_PATH') as $path) {
				$path = "{$path}/{$application_name}";
				if (is_dir($path))
					$application_path = $path;
			}
		}
		
		if ($application_path) {
			$application = new Application($this->dispatcher, 
						            $this->request,
						            $application_name,
						            $application_path);
			$this->applications[$application_name] = $application;
			return $application;
		}
		
		throw new CobwebConfigurationException(
			"Could not load application {$application_name}");
	}
	
	public function loadApplications() {
		foreach ($this->applications as $application)
			$application->load();
		return $this;
	}
	
	public function load($application_name) {
		$applications = $this->applications();
		return $applications[$application_name];
	}
	
	public function applications() {
		return $this->applications;
		
	}
	
	public function loadedApplications() {
		return $this->applications();
	}
}