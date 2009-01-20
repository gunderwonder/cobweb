<?php

class Permission extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('credential', 'string', 255, 
			array(
				'notnull' => true,
				'unique' => true
			
			)
		);
    }

	public function setUp() {
	
		$this->hasMany('Usergroup',
			array(
				'local' => 'permission_id',
				'foreign' => 'usergroup_id',
				'refClass' => 'UsergroupPermission'
			)
		);
		
		$this->hasMany('User',
			array(
				'local' => 'permission_id',
				'foreign' => 'user_id',
				'refClass' => 'UserPermission'
			)
		);
	}
	
	public function __toString() {
		return $this->credential;
	}
	
}
