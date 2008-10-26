<?php
/* $Id */

/**
 *
 */
class Router {
	
	private static $router = NULL;
	
	private static 
		$is_top_level = true, 
		$application_map = array();
		
	public function __construct() {
		
	}
	
	public static function connect(array $map) {
		if (self::$is_top_level) {
			Cobweb::set('URL_CONFIGURATION', $map);
			self::$is_top_level = false;
		} else
			self::$application_map = $map;
			
	}
	
	public static function map() {
		return self::$application_map;
	}
	
	public static function load($label, array $specification = NULL) {
		return new IncludeURLConfigurationAction($label, $specification);
	}
	
	public static function instance() {
		if (is_null(self::$router)) {
			self::$router = new Router();
		}
		return self::$router;
	}
	
}