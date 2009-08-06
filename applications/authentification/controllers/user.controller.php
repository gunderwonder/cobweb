<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Authentification
 * @version    $Revision$
 */
class UserController extends Controller {
	
	public function login($template_name = 'login.tpl') {
		if ($this->isPOST()) {
			$username = $this->POST->get('username', '');
			$password = $this->POST->get('password', '');
			
			$userclass = Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User');
			$user = $userclass::authenticate($username, $password);
			
			if ($user) {
				$this->request->session['cobweb-user-id'] = $user->id;
				if (!$this->request->isDirect())
					return new HTTPResponseRedirect($this->request['Referer']);
				else
					return new HTTPResponseRedirect(Cobweb::get('LOGIN_REDIRECT_URL'));
			} else
				$login_error = true;
			
		}
		
		return $this->render(
			$temaplate_name, array('login_error' => $login_error),
			$login_error ? HTTPResponse::UNAUTHORIZED : HTTPResponse::OK
		);
	}
	
	public function logout($redirect = NULL) {
		$this->request->session->end();
		$redirect_url = $redirect ? $redirect : Cobweb::get('LOGIN_URL');
		return $this->redirect($redirect_url);
	}
	
	public function logoutAndRedirectToLogin($login_url = NULL) {
		
		$this->request->user->logout();	
		return new $this->redirectToLogin($login_url);
	}
	
	public function redirectToLogin($login_url = NULL) {
		if (is_null($login_url))
			$login_url = Cobweb::get('LOGIN_URL');
			
		return new HTTPResponseRedirect($login_url);
	}
	
}