<?php

class UserPermission extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('permission_id', 'integer', NULL, array('primary' => true));
        $this->hasColumn('user_id', 'integer', NULL, array('primary' => true));
    }	
}
