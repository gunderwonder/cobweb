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
		$session_class = Cobweb::get('SESSION_STORAGE_CLASSNAME', 'Session');
		$this->session = new $session_class(Cobweb::instance()->request());

		if (!$this->session instanceof SessionStorage)
            throw new CobwebConfigurationException(
                "The `SESSION_STORAGE_CLASSNAME` class must implement SessionStorage"
            );
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