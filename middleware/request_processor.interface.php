<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Middleware
 * @version    $Revision$
 */
interface RequestProcessor {
	public function processRequest(Request $request);
	public function processResponse(Request $request, Response $response);
	public function processAction(Request $request, Action $action);
	public function processException(Request $request, Exception $exception);
}
