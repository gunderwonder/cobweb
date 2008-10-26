<?php

class RequiresAuthentification extends Annotation { }

class AuthentificationMiddleware extends Middleware {
	
	
	public function processAction(Request $request, Action $action) {
		$c = $action->reflection();
		
		Cobweb::log($c->getAnnotation('RequiresAuthentification'));
	}
	
	public function processRequest(Request $request) {
		// if (!isset($request->session))
		// 	throw new CobwebConfigurationException(
		// 		"Authentification middleware needs 'cobweb.session' middleware to be installed");
		// 		
		// 
	}
	
	
}