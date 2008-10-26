<?php
/* $Id$ */

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

		Doctrine::createTablesFromArray($models);
		
	}

}