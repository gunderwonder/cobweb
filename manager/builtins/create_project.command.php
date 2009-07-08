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
class CreateProjectCommand extends CobwebManagerCommand {
	
	public function configure() {
		$this->requiresArgumentCount(1);
		$this->usage('[project-name]');
	}
	
	public function execute() {

		$project_name = $this->arguments[0];
		
		$destination = getcwd() . '/' . $project_name . '/';
		$skeleton = realpath(dirname(__FILE__) . '/../project_template') . '/*';
		
		try {
			mkdir($destination);
		} catch (CobwebErrorException $e) {	
			if (preg_match('/File exists/', $e->getMessage()))
				$this->fail("Directory '{$destination}' exists.");
				
			throw $e;
		}
		
		system("cp -R {$skeleton} {$destination}");
		system("find {$destination} -type d -name '.svn' | xargs rm -rf");
		
		chdir($destination);
		
		$index_file = './www/index.php';
		$contents = file_get_contents($index_file);
		$contents = preg_replace('/%project_directory%/u', $destination, $contents);
		$contents = preg_replace('/%cobweb_directory%/u', COBWEB_DIRECTORY, $contents);
		file_put_contents($index_file, $contents);
		
		$this->info("Created project '{$project_name}' in {$destination}");
	}
	
	
}