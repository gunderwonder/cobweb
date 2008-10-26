<?php

class ShellCommand extends CobwebManagerCommand {
	
	public function configure() {
		$this->requiresProject();
	}
	
	public function execute() {
		foreach (Cobweb::get('INSTALLED_APPLICATIONS') as $application)
			Cobweb::loadApplication($application);
		
		require_once COBWEB_DIRECTORY . '/vendor/php-shell/PHP_Shell-0.3.0/scripts/php-shell-cmd.php';
	}
}