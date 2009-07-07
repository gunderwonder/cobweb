<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Cobweb manager command that creates database tables the specified model classes.
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Doctrine Application
 * @version    $Revision$
 */
class DoctrineBuildCommand extends CobwebManagerCommand {
	
	public function execute() {
		$models = count($this->arguments) == 0 ?
		          Doctrine::getLoadedModels() :
		          $this->arguments;

		try {
			Doctrine::createTablesFromArray($models);
		} catch (Exception $e) {
			$type = get_class($e);
			$this->fail("Caught {$type} while building models:\n{$e->getMessage()}");
		}
		
		$this->info('Built ' . count($models) . ' model(s) sucessfully');
		
	}

}