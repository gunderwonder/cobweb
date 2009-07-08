<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Management
 */
class CreateApplicationCommand extends CobwebManagerCommand {
	
	public function configure() {
		$this->requiresProject();
		$this->requiresArgumentCount(1);
	}
	
	public function execute() {
		
		if (!isset($this->arguments[0]))
			$this->manager->fail('Usage: cobweb create-application [project-name]');
		
		if (!is_dir(COBWEB_PROJECT_DIRECTORY . '/applications/'))
			$this->manager->fail('cobweb: Current working directory is not a Cobweb project directory');
			
		$application_name = $this->arguments[0];
		
		$destination = getcwd() . '/applications/' . $application_name . '/';
		$skeleton = realpath(dirname(__FILE__) . '/../application_template') . '/*';
		
		try {
			mkdir($destination);
		} catch (CobwebErrorException $e) {	
			$this->fail("Directory '{$destination}' exists.");
		}

		system("cp -R {$skeleton} {$destination}");
		system("find {$destination} -type d -name '.svn' | xargs rm -rf");
		
		print "cobweb: Successfully created application '{$application_name}'\n";
	}
	

}