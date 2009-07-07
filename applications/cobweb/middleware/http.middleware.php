<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * This middleware offers micellaneous HTTP related annotation checks and
 * finer grained HTTP header control. (NOTE: this is not ready for prime time yet)
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class HTTPMiddleware extends Middleware {
	
	
	public function processRequest(Request $request) {
		
	}
	
	public function processResponse(Request $request, Response $response) {
		
	}
	
	public function processAction(Request $request, Action $action) {
		if ($action->hasAnnotation('AllowedMethods')) {
			$allowed_methods = $action->annotation('AllowedMethods');
			if (!in_array($request->method, $allowed_methods))
				return new HTTPResponseMethodNotAllowed($allowed_methods);
		}
		
		$conditional_get = $request->isGET() && 
			(Cobweb::get('HTTP_CONDITIONAL_GET') || 
				$action->hasAnnotation('HTTPConditionalGET'));
		
		if ($conditional_get) {
			if (isset($request['If-Modified-Since']))
				return $this->checkModificationDate($request);
			else if (isset($request['If-None-Match'])
				return $this->checkContentHash($request);
		}
	}
}