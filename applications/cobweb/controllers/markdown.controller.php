<?php

require_once COBWEB_DIRECTORY . '/vendor/markdown/Markdown.php';

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.om>
 * @version    0.2
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class MarkdownController extends Controller {
	
	public function generate($slug, $base_path, $base_template) {
		
		$markdown_file = realpath($base_path . '/' . str_replace('-', '_', $slug) . '.mdown');

		if (!file_exists($markdown_file) || !str_starts_with($markdown_file, $base_path))
			throw new HTTP404();    
		
		$markdown = Markdown(file_get_contents($markdown_file));
		return $this->render($base_template, array('markdown' => $markdown));
	}
	
}


?>