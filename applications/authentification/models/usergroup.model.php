<?php

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