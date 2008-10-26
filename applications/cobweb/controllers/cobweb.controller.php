<?php

class CobwebController extends Controller {
	
	public function notFound404($uri = NULL) {
		
		if (!str_ends_with($this->request->path(), '/') && Cobweb::get('APPEND_SLASH_ON_404'))
			return new HTTPResponseRedirect(Cobweb::get('URL_PREFIX') . $this->request->path() . '/');
			
		return $this->render('404.tpl', 
			array('uri' => $this->request->URI()), 
			HTTPResponse::NOT_FOUND);
	}
	
}