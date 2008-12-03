<?php

class DebugMiddleware extends Middleware {
	
	public function processException(Request $request, Exception $e) {
		if (Cobweb::get('DEBUG'))
			return Controller::invoke('cobweb.debug.debugger', array('exception' => $e));
		else
			return Controller::invoke('cobweb.cobweb.exception', array('exception' => $e));
	}
		
}