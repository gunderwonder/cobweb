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
		if (method_exists($value, '__toSQL')) $value = $value->__toSQL();
		parent::__set($key, $value);
	}
}

?>