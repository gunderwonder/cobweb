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
class MemcachedCacheEngine extends CacheEngine {
	
	protected $memcached = NULL;
	protected $memcached_backend = NULL;
	
	protected function connection() {
		if (is_null($this->memcached)) {
			$this->memcached = new Memcache();
			$this->memcached->connect($this->hostname, $this->port ? $this->port : 11211);
		}
		return $this->memcached;
	}
	
	public function get($key, $default = NULL) {
		$cache = $this->connection()->get($key);
		return $cache ? $cache : $default;
	}
	
	public function set($key, $value, $timeout = NULL) {
		$timeout = $timeout ? $timeout : Cobweb::get('CACHE_TIMEOUT', 3600);
		$this->connection()->set($key, $value, NULL, $timeout);
	}
	
	public function delete($key) {
		$this->connection()->delete($key);
	}
	
	public function touch($key, $timeout = NULL) {
		$cache = $this->get($key);
		$timeout = $timeout ? $timeout : Cobweb::get('CACHE_TIMEOUT', 3600);
		$this->connection()->replace($key, $cache, $timeout);
		
	}
}