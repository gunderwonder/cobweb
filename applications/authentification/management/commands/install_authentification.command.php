<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Authentificatio
 * @version    $Revision$
 */
class InstallAuthentificationCommand extends CobwebManagerCommand {
	
	public function execute() {
		$this->info('Creating authentification tables...');
		
		$userclass = Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User');
		Doctrine::createTablesFromArray(array(
			$userclass, 
			'Usergroup', 
			'Permission', 
			'UsergroupPermission', 
			'Userpermission')
		);
		
		$userclass = Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User');
		$su = new $userclass;
		
		$su->username = $this->prompt('Superuser username');
		$su->setPassword($this->prompt('Superuser password'));
		$su->email = $this->prompt('Superuser email adress');
		$su->firstname = $this->prompt('Superuser first name');
		$su->lastname = $this->prompt('Superuser last name');
		$su->is_superuser = true;
		
		$this->info('Creating superuser...');
		$su->save();
		$this->info('Authentification application installed.');
		
	}

}