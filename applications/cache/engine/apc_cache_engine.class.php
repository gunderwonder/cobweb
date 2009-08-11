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
class APCCacheEngine extends CacheEngine {
	
	protected function initialize() {
		if (!function_exists('apc_cache_info'))
			throw new CobwebException('The APC extension required to use this cache backend!');
	}
	
	public function get($key, $default = NULL) {
		$cache = apc_fetch($key);
		return $cache !== false ? $cache : $default;
	}
	
	public function set($key, $value, $timeout = NULL) {
		$timeout = $timeout ? $timeout : Cobweb::get('CACHE_TIMEOUT', 3600);
		apc_store($key, $value, $timeout);
	}
	
	public function delete($key) {
		
		apc_delete($key);
	}
	
	public function touch($key, $timeout = NULL) {
		$cache = $this->get($key);
		$timeout = $timeout ? $timeout : Cobweb::get('CACHE_TIMEOUT', 3600);
		apc_store($key, $cache, $timeout);
	}
}