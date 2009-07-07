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
class ConditionalGetMiddleware extends Middleware {
	
	public function processResponse(Request $request, Response $response) {
		
		if (isset($response['Etag']) && 
		    	isset($request['If-None-Match']) &&
		    	$response['Etag'] == $request['If-None-Match']) {
			return new HTTPResponseNotModified();
		}
		
		if (isset($response['Last-Modified']) && 
		    	isset($request['If-Modified-Since'])) {
			 
			$modification_time = strtotime($response['Last-Modified']);
			$conditional = strtotime($response['If-Modified-Since']);
			if ($conditional <= $modification_time)
				return new HTTPResponseNotModified();
		}
		return $response;
	}
}