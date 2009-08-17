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
 * @deprecated
 */
class DebugMiddleware extends Middleware {
	
	public function initialize() {
		Cobweb::warn('DebugMiddleware is deprecated, use CobwebMiddleware instead');
		$this->middleware = new CobwebMiddleware($this->dispatcher);
	}
	
	public function processException(Request $request, Exception $e) {
		return $this->middleware->processException($request, $e);
	}

}