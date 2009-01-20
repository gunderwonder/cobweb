<?php



class AuthentificationMiddleware extends Middleware {
	
	public function initialize() {
		
	}
	
	public function processAction(Request $request, Action $action) {
		if (isset($request->user))
			return;
		
		if (($user_id = $request->session->get('cobweb-user-id', false))) {
			
			$user = Model::table('User')->find($user_id);
			if ($user) {
				$request->user = $user;
				$request->user->setAuthenticated(true);
				if (!$request->user)
					$request->user = new User();
		
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