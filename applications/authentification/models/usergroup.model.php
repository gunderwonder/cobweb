<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Authentification
 * @version    $Revision$
 */
class Usergroup extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('name', 'string', 255, array('notnull' => true));
    }

	public function setUp() {

		$this->hasMany('Permission as Permissions',
			array(
				'local' => 'usergroup_id',
				'foreign' => 'permission_id',
				'refClass' => 'UsergroupPermission'
			)
		);
	}
	
	public function hasPermission($credential) {
		foreach ($this->Permissions as $p)
			if ($p->credential == $credential)
				return true;
				
		return false;
	}
	
	public function hasPermissions(array $credentials) {
		foreach ($credentials as $c)
			if (!$this->hasPermission($c))
				return false;
				
		return true;
	}
	
	public function permissions() {
		$permissions = array();
		foreach ($this->Permissions as $permission)
			$permissions[] = $permission->credential;
		return $permissions;
	}
	
	public static function table() {
		return Doctrine::getTable('usergroup');
	}
	
}