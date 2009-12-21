<?php

class RequiresHTTPAuthentification extends ActionAnnotation {
	
	public $realm;
	public $login_failure_body = 'Access unauthorized';
	
	public function processRequest(Request $request) {

		if (!isset($request->META['PHP_AUTH_USER']))
			return new HTTPResponseUnauthorized($this->login_failure_body, $this->realm);
		
		$username = $request->META['PHP_AUTH_USER'];
		$password = $request->META['PHP_AUTH_PW'];
		
		$userclass = Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User');
		$user = call_user_func(array($userclass, 'authenticate'), $username, $password);
		
		if (!$user)
			return new HTTPResponseUnauthorized($this->login_failure_body, $this->realm);
		else
			$request->user = $user;
	}
	
	protected function checkConstraints($target) {
		if (!$this->realm)
			throw new CobwebException('Provide a name for this authentification realm');
	}
	
}