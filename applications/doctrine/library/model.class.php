<?php


abstract class Model extends Doctrine_Record {
	
	public static function table($model_name) {
		return Doctrine::getTable($model_name);
	}
	
	public static function query($model_name = NULL, $query_alias = '') {
		if (is_null($model_name))
			return Doctrine_Query::create();
		
		return Doctrine::getTable($model_name)->createQuery($query_alias);
	}
	
	public function __set($key, $value) {
		if (is_object($value) && method_exists($value, '__toSQL')) 
			$value = $value->__toSQL();
			
		parent::__set($key, $value);		
	}
}

?>