<?php
/**
 * @version $Id$
 */


/**
 * $Id$
 * 
 * @package Cobweb
 * @subpackage Session
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version 0.1
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