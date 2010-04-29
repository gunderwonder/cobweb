<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Cobweb Application
 * @version    $Revision$
 */
class CobwebController extends Controller {
	
	const STATIC_PATH_RE = '^static/cobweb/(?<path>.+)';
	
	/**
	 * @param $uri       string
	 * @param $exception Exception
	 * @return HTTPResponse
	 * 
	 * @LoggingDisabled
	 */
	public function notFound404($uri = NULL, Exception $exception = NULL) {
		
		if (!str_ends_with($this->request->path(), '/') && Cobweb::get('APPEND_SLASH_ON_404'))
			return new HTTPResponseRedirect(Cobweb::get('URL_PREFIX') . $this->request->path() . '/');
		
		if (Cobweb::get('DEBUG'))
			throw new HTTP404();
			
		return $this->render(Cobweb::get('HTTP404_TEMPLATE', '404.tpl'), 
			array('uri' => $this->request->URI()), 
			HTTPResponse::NOT_FOUND,
			MIMEType::HTML, 
			TemplateAdapter::PHP
		);
	}
	
	/**
	 * @param  $exception Exception 
	 * @return HTTPResponse
	 */
	public function gracefulException(Exception $exception) {
		return $this->render(Cobweb::get('HTTP500_TEMPLATE', '500.tpl'), array(
				'exception' => $exception,
				'exception_class' => get_class($exception),
			),
			HTTPResponse::INTERNAL_SERVER_ERROR
		);
	}
	
	public function start() {
		return $this->render(
			'/debug/cobweb_installed.tpl', 
			array(), 
			HTTPResponse::OK, 
			MIMEType::HTML, 
			TemplateAdapter::PHP
		);
	}
	
	public function serveStaticData($path) {
		return self::invoke('cobweb.filesystem.serve', array(
			'base_path' => COBWEB_DIRECTORY . '/applications/cobweb/static/',
			'path' => $path
		));
	}
	
}