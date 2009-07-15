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
class CobwebCache extends Model {
	
	public function setTableDefinition() {
		$this->hasColumn('cache_key', 'string', NULL, array('unique' => true, 'notnull' => true));
		$this->hasColumn('cached_value', 'string', NULL, array('notnull' => true));
		$this->hasColumn('expiration', 'timestamp', NULL, array('notnull' => true));
	}
	
	public function hasExpired(CWDateTime $now = NULL) {
		$now = is_null($now) ? new CWDateTime() : $now;
		if (in_array(CWDateTime::comparator($now, new CWDateTime($this->expiration)), array(0, 1)))
			return true;
			
		return NULL;
	}
}