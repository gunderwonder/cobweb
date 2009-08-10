<?php

class CreateCacheTableCommand extends CobwebManagerCommand {
	
	public function execute() {
		Doctrine::createTablesFromArray(array('CobwebCache'));
		$this->info('Sucessfully created cache table.');
	}
}