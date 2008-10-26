<?php

class CobwebDoctrineManager {
	
	private static $connections;
	
	public static function loadModels($caching = false) {
		
		$applications = Cobweb::get('INSTALLED_APPLICATIONS');
		foreach ($applications as $application) {
			
			foreach (Cobweb::get('APPLICATIONS_PATH') as $p) {
				$models_path = "{$p}/{$application}/models";
			
				if (file_exists($models_path))
					foreach (new DirectoryIterator($models_path) as $file)
						if (!$file->isDir() && 
						    	!$file->isDot() &&
						    	!str_starts_with($file->getFilename(), '.'))
							require_once $file->getPathname();
			}	
		}
	}
	
	public static function connect($dsn) {
		if (!is_array(self::$connections))
			self::$connections = array();

		$manager = Doctrine_Manager::getInstance();
		$manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
		  
		$connection = $manager->openConnection($dsn);
		$connection->setCharset('utf8');
		$connection->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
		Cobweb::log('Connected to database %o', $connection);
		
		self::$connections[] = $connection;
	}
	
	public static function connections() {
		return self::$connections;
	}
	
	
}