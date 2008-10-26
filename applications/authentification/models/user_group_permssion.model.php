<?php

// many-to-many: usergroup <-> permission
class UsergroupPermission extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('permission_id', 'integer', NULL, array('primary' => true));
        $this->hasColumn('usergroup_id', 'integer', NULL, array('primary' => true));
    }	
}
