<?php
/**
 * @version $Id$
 */

/**
 * @package    Cobweb
 * @subpackage Dispatch
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 */
class MiddlewareManager {
	
	protected static $manager;
	
	protected 
		$middleware,
		$middleware_reversed,
		$dispatcher,
		$application_manager;
	
	public function __construct(Dispatcher $dispatcher, ApplicationManager $application_manager) {
		$this->dispatcher = $dispatcher;
		$this->application_manager = $application_manager;
	}
	
	public function handleRequest(Request $request) {
		foreach ($this->middleware as $middleware)
			if (($response = $middleware->processRequest($request)))
				return $this->assertResponse($response);
				
		return NULL;
	}
	
	public function handleResponse(Request $request, Response $response = NULL) {
		foreach ($this->middleware_reversed as $middleware)
			$response = $this->assertResponse($middleware->processResponse($request, $response), $middleware);
				
		return $this->assertResponse($response, $middleware);
	}
	
	public function handleException(Request $request, Exception $exception) {
		foreach ($this->middleware_reversed as $middleware)
			if (($response = $middleware->processException($request, $exception)))
				return $this->assertResponse($response, $middleware);
		return NULL;
	}
	
	public function handleAction(Request $request, Action $action) {
		foreach ($this->middleware as $middleware)
			if (($response = $middleware->processAction($request, $action)))
				return $this->assertResponse($response, $middleware);
		return NULL;
	}
	
	public function load() {
		$this->middleware = array();
		foreach (Cobweb::get('INSTALLED_MIDDLEWARE') as $middleware_name) {
			
			$middleware_class = self::classify($middleware_name);
			
			if (!class_exists($middleware_class))
				require_once self::pathify($middleware_name);
				
			if (!class_exists($middleware_class))
				throw new CobwebMiddlewareException(
					"Class {$middleware_class} (with label '{$middleware_name}') is not defined");
			
			$this->middleware[] = new $middleware_class($this->dispatcher);
		}
		
		$this->middleware_reversed = array_reverse($this->middleware);
		
		if (Cobweb::get('DEBUG')) {
			$middleware_names = array();
			foreach ($this->middleware as $m)
				$middleware_names[] = get_class($m);
				
			Cobweb::info('Loaded middleware: %o', $middleware_names);
		}	
		
	}
	
	protected function assertResponse($response, Middleware $middleware) {
		if (!($response instanceof HTTPResponse))
			throw new CobwebMiddlewareException(
				get_class($middleware) . ' did not return HTTPResponse, got ' . 
				gettype($response));
		return $response;
	}
	
	/**
	 * Throws an exception if the specified middleware class does not exist.
	 * 
	 * @param  string $middleware_class  name of the middleware class
	 * @param  string $middleware_name   label of the middlware
	 * 
	 * @throws CobwebMiddlewareException
	 */
	protected function assertMiddleware($middleware_class, $middleware_name) {
		if (!class_exists($middleware_class))
			throw new CobwebMiddlewareException(
				"Unable to load middleware {$middleware_name}, " .
				"class {$middleware_class} does not exist");
	}
	
	/**
	 * Convert a middleware label of the form `<application_name>.<foo>'
	 * to its class name `FooMiddleware'
	 * 
	 * @return string class name of a middleware label
	 */
	protected static function classify($middleware_name) {
		list($application, $middleware) = explode('.', $middleware_name);
		return str_classify($middleware) . 'Middleware';
	}
	
	/**
	 * Suggests the full path to a file containg the middleware class specified by
	 * its label. Throws exception if the file does not exist.
	 * 
	 * @param  string $middleware_name label
	 * @return path of the specified middleware
	 *  
	 * @throws CobwebMiddlewareException
	 */
	protected static function pathify($middleware_name) {
		$path = explode('.', $middleware_name);
		if (count($path) != 2)
			throw new CobwebConfigurationException(
				"Invalid name of middleware '{$middleware_name}'");
		
		$application = Cobweb::loadApplication($path[0]);
		$path = $application->path() . "/middleware/{$path[1]}.middleware.php";
		
		if (!file_exists($path) || !is_file($path))
			throw new FileNotFoundException(
				"Unable to load middleware '{$middleware_name}' from '$path'");
			
		return $path;
			
	}
	
	public function __toArray() {
		return $this->middleware;
	}
}