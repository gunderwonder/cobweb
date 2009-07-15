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
class DatabaseCacheEngine extends CacheEngine {
	
	protected function getCacheRecord($key) {
		$cache = Model::query('CobwebCache', 'c')
			->select('c.cache_key, c.cached_value')
			->where('c.cache_key = ?', $key)
			->fetchOne();
		if (!$cache)
			$cache = new CobwebCache();
		return $cache;
	}
	
	public function get($key, $default = NULL) {

		$cache = Model::query('CobwebCache', 'c')
			->where('c.cache_key = ?', $key)
			->select('c.cached_value')
			->fetchOne();
		if (!$cache || $cache->hasExpired())
			return $default;
			
		return unserialize($cache->cached_value);
	}
	
	public function set($key, $value, $timeout = NULL) {
		$cache = $this->getCacheRecord($key);
		$timeout = $timeout ? $timeout : Cobweb::get('CACHE_TIMEOUT', 3600);
		$cache->cache_key = $key;
		$cache->expiration = CWDateTime::create()->modify("+{$timeout} seconds");
		$cache->cached_value = serialize($value);
		$cache->save();			
	}
	
	public function delete($key) {
		Model::query('CobwebCache', 'c')
			->delete()
			->where('c.cache_key = ?', $key)
			->execute();
	}
	
	public function touch($key, $timeout = NULL) {
		$cache = $this->getCacheRecord($key);
		$cache->expiration = CWDateTime::create()->modify("+{$timeout} seconds");
	}
}