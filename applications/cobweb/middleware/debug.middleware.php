<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once COBWEB_DIRECTORY . '/applications/cobweb/middleware/cobweb.middleware.php';

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class DebugMiddleware extends Middleware {
	
	private $error = false;
	
	public function initialize() {
		$this->middleware = new CobwebMiddleware($this->dispatcher);
	}
	
	public function processException(Request $request, Exception $e) {
		return $this->middleware->processException($request, $e);
	}

}