<?php

class Usergroup extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('name', 'string', 255, array('notnull' => true));
    }

	public function setUp() {

		$this->hasMany('Permission',
			array(
				'local' => 'usergroup_id',
				'foreign' => 'permission_id',
				'refClass' => 'UsergroupPermission'
			)
		);
	}
	
	public function hasPermission($credential) {
		foreach ($this->Permission as $p)
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
	
	public function getPermissions() {
		return array_map(
			create_function('$p', 'return $p->credential;'),
			is_array($this->Permission) ? $this->Permission : array()
		);
	}
	
	public static function table() {
		return Doctrine::getTable('usergroup');
	}
	
}