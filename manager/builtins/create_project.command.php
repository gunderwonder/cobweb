<?php

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
		chmod($destination . '/templates/compiled', 0777);
		$this->info("Created project '{$project_name}' in {$destination}");
	}
	
	
}