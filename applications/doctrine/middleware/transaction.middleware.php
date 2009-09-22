<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Doctrine
 * @version    $Revision$
 */
class TransactionMiddleware extends Middleware {
	
	public function processRequest(Request $request) {
		foreach (CobwebDoctrineManager::connections() as $connection)
			$connection->beginTransaction();
	}
	
	public function processException(Request $request, Exception $e) {
		foreach (CobwebDoctrineManager::connections() as $connection)
			$connection->rollback();
	}
	
}