<?php
/**
 * $Id$
 * 
 * @package Cobweb
 * @subpackage Session
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version 0.1
 */

class SessionMiddleware extends Middleware {
	
	public function processRequest(Request $request) {
		Console::log('Initializing session middleware...');
		
		
		$request->session = new Session();	
		Console::info('Session data: %o', $request->session);
	}
	
	
}



?>