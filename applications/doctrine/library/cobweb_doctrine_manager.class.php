<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 * @package    Cobweb
 * @subpackage Doctrine Application
 */
class CobwebDoctrineManager {
	
	private static $connections;
	private static $cached_model;
	
	public static function loadModels($lazy_load = false) {
				
		$applications = Cobweb::get('INSTALLED_APPLICATIONS');
		foreach ($applications as $application) {
			
			foreach (Cobweb::get('APPLICATIONS_PATH') as $p) {
				$models_path = "{$p}/{$application}/models";
			
				if (file_exists($models_path))
					foreach (new DirectoryIterator($models_path) as $file)
						if (!$file->isDir() && 
						    	!$file->isDot() &&
						    	!str_starts_with($file->getFilename(), '.')) {
							
							if (!$lazy_load) {
								require_once $file->getPathname();
							} else {
								
								CobwebLoader::register(
									self::classify($file->getFilename()), 
									$file->getPathname()
								);
							}
							
						}	
			}	
		}
	}
	
	public static function connect($dsn) {
		if (!is_array(self::$connections))
			self::$connections = array();

		$manager = Doctrine_Manager::getInstance();
		$manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
		  
		$connection = Doctrine_Manager::connection($dsn);
		$connection->setCharset('utf8');
		$connection->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
		Cobweb::log('Connected to database %o', $connection);
		
		self::$connections[] = $connection;
	}
	
	public static function connections() {
		return self::$connections;
	}
	
	private static function classify($filename) {
		$dotoffset = strpos($filename, '.');
		if ($dotoffset === false)
			return NULL;
			
		$model_name = substr($filename, 0, $dotoffset);
		return str_classify($model_name);
	}

}