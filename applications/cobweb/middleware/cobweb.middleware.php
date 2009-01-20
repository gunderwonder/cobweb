<?php

class CobwebMiddleware extends Middleware {
	private $error = false;
	
	public function processException(Request $request, Exception $e) {
		
		if ($this->error)
			return NULL;
		$this->error = true;

		if (Cobweb::get('DEBUG'))
		
			return Controller::invoke('cobweb.debug.debugger', array('exception' => $e));
		else if ($e instanceof HTTP404)
			return Controller::invoke('cobweb.cobweb.not_found_404');
		else
			return Controller::invoke('cobweb.cobweb.graceful_exception', array('exception' => $e));
			
	}
}