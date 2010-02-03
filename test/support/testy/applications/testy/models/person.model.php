<?php

class Person extends Model {
	public function setTableDefinition() {
		$this->hasColumn('first_name', 'string');
		$this->hasColumn('last_name', 'string');
		$this->hasColumn('birthday', 'timestamp');
	}
}