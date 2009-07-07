<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Cobweb application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class SessionMiddleware extends Middleware {
	
	private $session;
	
	public function initialize() {
		Cobweb::log('Initializing session middleware...');
		$this->session = new Session();
	}
	
	public function processRequest(Request $request) {
		if (!isset($request->session))
			$request->session = $this->session;	
		
	}
	
	public function processResponse(Request $request, Response $response) {
		Cobweb::info('Session data: %o', $request->session);
		return $response;
	}
	
	
}



?>