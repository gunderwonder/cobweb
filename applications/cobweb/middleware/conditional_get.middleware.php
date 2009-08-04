<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class ConditionalGETMiddleware extends Middleware {
	
	public function processResponse(Request $request, Response $response) {
		if (!$request->isGET())
			return $response;
		
		$etag = isset($response['Etag']) ? $response['Etag'] : NULL;
		$etag_query = isset($response['If-None-Match']) ? $response['If-None-Match'] : NULL;
		if ($etag && $etag_query && $etag === $etag_query)
			return new HTTPResponseNotModified();
			
		$last_modified = isset($response['Last-Modified']) ? 
			new CWDateTime($response['Last-Modified']) : NULL;
		$last_modified_query = isset($request['If-Modified-Since']) ? 
			new CWDateTime($request['If-Modified-Since']) : NULL;
		
		if ($last_modified && 
			$last_modified_query && 
			$last_modified_query->compare($last_modified) >= 0)
			return new HTTPResponseNotModified();
		
		return $response;
	}
	
}