<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Cache
 * @version    $Revision$
 */
class CacheStore {
	
	/** @var CacheEngine */
	protected static $engine = NULL;
	
	/** @var array */
	protected static $scheme_mappings = array(
		'db' => 'DatabaseCacheEngine',
		'memcached' => 'MemcachedCacheEngine'
	);
	
	public static function initialize() {
		$engine_uri = parse_url(Cobweb::get('CACHE_ENGINE', 'db://cobweb_cache'));

		$scheme = $engine_uri['scheme'];
		if (!isset(self::$scheme_mappings[$scheme]))
			throw new CobwebConfigurationException("No cache engine defined for the '{$scheme}' scheme");
			
		self::$engine = new self::$scheme_mappings[$scheme](
			Cobweb::instance()->dispatcher(), 
			Cobweb::instance()->request(),
			new ImmutableArray($engine_uri)
		);
	}
	
	public static function get($key, $default = NULL) {
		return self::$engine->get($key, $default);
	}
	
	public static function set($key, $value, $expiration = NULL) {
		self::$engine->dispatcher()->fire('cache.updated', array('key' => $key));
		return self::$engine->set($key, $value, $expiration);
	}
	
	public static function delete($key) {
		self::$engine->dispatcher()->fire('cache.deleted', array('key' => $key));
		return self::$engine->delete($key);
	}
	
	public static function touch($key, $expiration = NULL) {
		return self::$engine->touch($key, $expiration);
	}
	
}