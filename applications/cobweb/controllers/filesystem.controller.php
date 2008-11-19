<?php
/* $Id$ */

/**
 * @author  Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version 0.2
 */
class FilesystemController extends Controller {
	
	/**
	 * Returns a response with the contents of a file outside the web server's
	 * document root.
	 * 
	 * As a general rule, it is better to let the webserver (such as Apache)
	 * handle file downloads. Only use this action if you have no other choice!
	 * 
	 * @param  $path        name/path of the file to serve
	 * @param  $base_path   base path in which the file resides
	 * @return HTTPResponse response containing the file with a MIME type guessed from its filename
	 * @throws HTTP404      if the file does not exist 
	 */
	public function serve($path, $base_path) {
		$file = realpath($base_path . '/' . $path);
		if (!str_starts_with($file, $base_path) || !file_exists($file))
			throw new HTTP404();
		
		// TODO: add conditional GET support based on modification date
		return $this->respond(
			file_get_contents($file), 
			HTTPResponse::OK, 
			MIMEType::guess(basename($file)));
		
	}
}