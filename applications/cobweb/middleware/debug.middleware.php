<?php

require_once COBWEB_DIRECTORY . '/applications/cobweb/middleware/cobweb.middleware.php';

/**
 * @deprecated
 */
class DebugMiddleware extends Middleware {
	
	public function initialize() {
		$this->middleware = new CobwebMiddleware($this->dispatcher);
	}
	
	private $error = false;
	
	public function processException(Request $request, Exception $e) {
		return $this->middleware->processException($request, $e);
	}

}