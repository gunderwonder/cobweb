<?php

class UserController extends Controller {
	
	public function login($template) {
	
	}
	
	public function logout($template_name) {
		$this->request->user->logout();
		return $this->render($template_name);
	}
	
	public function logoutAndRedirectToLogin($login_url = NULL) {
		if (is_null($login_url))
			$login_url = Cobweb::get('AUTHENTIFICATION_LOGIN_URL');
		
		$this->request->user->logout();	
		return new HTTPResponseRedirect($login_url);
	}
	
	public function redirectToLogin($login_url = NULL) {
		
	}
	
}