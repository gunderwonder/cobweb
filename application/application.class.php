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
class Application {
	
	protected 
		$name,
		$path;
	
	public function __construct(Dispatcher $dispatcher, 
		                        Request $request,
		                        $application_name,
		                        $path) {
			
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->name = $application_name;
		$this->path = $path;
		
		$this->initialize();
	}
	
	protected function initialize() {
		$application_path_constant = $this->pathConstantName();
		if (!defined($application_path_constant))
			define($application_path_constant, $this->path);
		
		if (file_exists($this->path . '/settings/bootstrap.inc.php'))
			require_once $this->path . '/settings/bootstrap.inc.php';
	}
	
	protected function pathConstantName() {
		return strtoupper(str_replace('-', '_', $this->name)) . '_APPLICATION_DIRECTORY';
	}
	
	public function path() {
		return $this->path;
	}
}