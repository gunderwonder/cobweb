<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Redirects application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class RedirectFallbackMiddleware extends Middleware {
	
	public function processResponse(Request $request, Response $response) {

		if ($response->code() != HTTPResponse::NOT_FOUND)
			return $response;
		
		$site_id = Cobweb::get('SITE_ID', false);
		$redirect = Redirect::find($request->URI(), $site_id ? $site_id : NULL);
		
		if (!$redirect && Cobweb::get('APPEND_SLASH_ON_404'))
			$redirect = Redirect::find(rtrim($request->URI(), '/'), $site_id ? $site_id : NULL);
		
		if ($redirect)
			return $redirect->new_url ?
				new HTTPResponsePermanentRedirect($redirect->new_url) :
				new HTTPResponseGone();
				
		return $response;
		
	}
}