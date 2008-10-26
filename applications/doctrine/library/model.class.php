<?php


abstract class Model extends Doctrine_Record {
	
	public static function table($model_name) {
		return Doctrine::getTable($model_name);
	}
	
	public static function query($model_name = NULL, $query_alias = '') {
		if ($model_name == NULL)
			return Doctrine_Query::create();
		
		return Doctrine::getTable($model_name)->createQuery($query_alias);
	}
	
	// public function __call($method, $arguments) {
	// 	if (count($arguments) != 1)
	// 		return parent::__call($method, $arguments);
	// 	
	// 	$matches = array();
	// 	if (preg_match('/^has(.*)$/', $method, $matches) === false || count($matches) != 2)
	// 		return parent::__call($method, $arguments);
	// 
	// 			
	// 	$this->hasRelation($relation);
	// }
	// 
	// private function hasRelation_($relation) {
	// 	if (!isset($this->$relation))
	// 		return false;
	// 	foreach ($this->$relation as $r)
	// 		if ($r->id == $arguments[0])
	// 			return true;
	// }
	
}

?>