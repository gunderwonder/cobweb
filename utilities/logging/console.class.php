<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Logging
 */
class Console {
	
	private static $logger = NULL;
	
	public static function log() {
		return self::logger()->log(func_get_args());
		return self::logger()->log(array_merge(array($string), $arguments));
	}
	
	public static function warn() {
		return self::logger()->warn(func_get_args());
	}
	
	public static function info() {
		return self::logger()->info(func_get_args());
	}
	
	public static function assert() {
		return self::logger()->info(func_get_args());
	}
	
	public static function error() {
		return self::logger()->error(func_get_args());
	}
	
	public static function logger() {
		if (is_null(self::$logger)) {
			self::$logger = new Logger('Console');
		}
		
		return self::$logger;
	}
}