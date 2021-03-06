<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Doctrine
 * @version    $Revision$
 */
class DoctrineMigrateCommand extends CobwebManagerCommand {
	
	public function configure() {
		// $this->requiresProject();
	}
	
	public function execute() {
		
		if (count($this->arguments) == 0)
			$this->manager->fail('usage: cobweb doctrine-migrate [application-name] (migration-index)');
		
		$application_name = $this->arguments[0];
		$migration_index = isset($this->arguments[1]) ? $this->arguments[1] : NULL;
		if (!is_null($migration_index) && !ctype_digit($migration_index))
			$this->manager->fail('cobweb: Invalid migration index');
		
		$application = Cobweb::loadApplication($application_name);
		$path = $application->path() . '/models/migrations/';
		if (!file_exists($path))
			$this->fail('cobweb: The specified application does not contain any migrations');
		
		$migration = new Doctrine_Migration($path);
		
		$new_index = 0;
		try {
			if (is_null($migration_index))
				$new_index = $migration->migrate();
			else
				$new_index = $migration->migrate($migration_index);
			
		} catch (Exception $e) {
			$class = get_class($e);
			$this->manager->fail("Migration error '{$class}'. The error was: '{$e->getMessage()}'");
		}
		$this->info("Sucessfully migrated {$application_name} to version {$new_index}");
	}
	
}