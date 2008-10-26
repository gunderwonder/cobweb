<?php


class DocumentationController extends Controller {
	
	public function manual($slug = NULL) {
		return self::invoke('cobweb.markdown.generate',
			array(
				'slug' => is_null($slug) ? 'index' : $slug,
				'base_path' => COBWEB_DIRECTORY . '/documentation/manual',
				'base_template' => '/documentation/cobweb_manual.tpl' 
			)
		);
	}
	
	
}