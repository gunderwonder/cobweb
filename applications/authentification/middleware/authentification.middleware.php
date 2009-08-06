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
class AuthentificationMiddleware extends Middleware {
		
	public function processAction(Request $request, Action $action) {
		if (isset($request->user))
			return;
		
		if (($user_id = $request->session->get('cobweb-user-id', false))) {
			
			$userclass = Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User');
			$user = Model::table($userclass)->find($user_id);
				
			if ($user) {
				$request->user = $user;
				$request->user->setAuthenticated(true);
				if (!$request->user)
					$request->user = new $userclass();
		
				Cobweb::log('Authenticated user %o', $request->user);
			} else
				unset($request->session['cobweb-user-id']);
		}
		
		if ($request->isAuthenticated() &&
		    $action->hasAnnotation('RequiresPermission')) {
			
			$permissions = $action->annotation('RequiresPermission')->value;
			if (!is_array($permissions))
				$permissions = array($permissions);
			
			if (!$request->user->hasPermissions($permissions))
				return new HTTPResponseRedirect(Cobweb::get('LOGIN_URL'));
		}
		
		if (!$request->isAuthenticated() &&
			$request->path() != Cobweb::get('LOGIN_URL') &&
			$action->hasAnnotation('RequiresAuthentification')) {
			
			return new HTTPResponseRedirect(Cobweb::get('LOGIN_URL'));
		}
	}
	
	
}