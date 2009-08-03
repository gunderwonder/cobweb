<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


/**
 * The {@link MiddlewareManager} provides an interface to the Cobweb's 
 * middleware framework. It is responsible for loading the appropriate middleware
 * classes based on the `INSTALLED_MIDDLEWARE` setting and passing request, response
 * and action instances with the middleware objects it manages.
 * 
 * @package    Cobweb
 * @subpackage Dispatch
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 */
class MiddlewareManager {
	
	protected static $manager;
	
	/** @var array */
	protected $middleware;
	
	/** @var array */
	protected $middleware_reversed;
	
	/** @var Dispatcher */
	protected $dispatcher;
	
	/** @var ApplicationManager */
	protected $application_manager;
	
	/**
	 * Instantiates a {@link MiddlewareManager} with the specified {@link Dispatcher}
	 * and {@link ApplicationManager}
	 */
	public function __construct(Dispatcher $dispatcher, ApplicationManager $application_manager, array $middleware = array()) {
		$this->dispatcher = $dispatcher;
		$this->application_manager = $application_manager;
		$this->middleware = $middleware;
		$this->middleware_reversed = $middleware;
	}
	
	/**
	 * Runs the specified request through the middleware objects
	 * of this manager.
	 * 
	 * Each middleware object is called in order with the 
	 * {@link Middleware::processRequest()} method. If a middleware object
	 * returns a {@link Response} object, the loop short curcuits and returns
	 * the given response; otherwise NULL is returned.
	 * 
	 * @param  Request  $request
	 * @return Response|NULL
	 */
	public function handleRequest(Request $request) {
		foreach ($this->middleware as $middleware)
			if (($response = $middleware->processRequest($request)))
				return $this->assertResponse($response);
				
		return NULL;
	}
	
	/**
	 * Runs the specified response through the middleware objects
	 * of this manager.
	 * 
	 * Each middleware object is called in reverse order with the 
	 * {@link Middleware::processResponse()} method. Each middleware is expected to,
	 * return a response, which is passed to next middleware instance etc.;
	 * otherwise a {@link CobwebMiddlewareException} is thrown. 
	 * 
	 * @param  Request  $request
	 * @return Response|NULL
	 * @throws CobwebMiddlewareException
	 */
	public function handleResponse(Request $request, Response $response = NULL) {
		foreach ($this->middleware_reversed as $middleware)
			$response = $this->assertResponse($middleware->processResponse($request, $response), $middleware);
				
		return $this->assertResponse($response, $middleware);
	}
	
	/**
	 * Runs the specified exception through the middleware objects
	 * of this manager.
	 * 
	 * Each middleware object is called in reverse order with the 
	 * {@link Middleware::processException()} method. If a middleware object
	 * returns a {@link Response} object, the loop short curcuits and returns
	 * the given response; otherwise NULL is returned.
	 * 
	 * @param  Request  $request
	 * @return Response|NULL
	 * @throws CobwebMiddlewareException
	 */
	public function handleException(Request $request, Exception $exception) {
		foreach ($this->middleware_reversed as $middleware)
			if (($response = $middleware->processException($request, $exception)))
				return $this->assertResponse($response, $middleware);
		return NULL;
	}
	
	/**
	 * Runs the specified action through the middleware objects
	 * of this manager.
	 * 
	 * Each middleware object is called in order with the 
	 * {@link Middleware::processAction()} method. If a middleware object
	 * returns a {@link Response} object, the loop short curcuits and returns
	 * the given response; otherwise NULL is returned.
	 * 
	 * @param  Request  $request
	 * @return Response|NULL
	 * @throws CobwebMiddlewareException
	 */
	public function handleAction(Request $request, Action $action) {
		foreach ($this->middleware as $middleware)
			if (($response = $middleware->processAction($request, $action)))
				return $this->assertResponse($response, $middleware);
		return NULL;
	}
	
	/**
	 * Throws a {@link CobwebMiddlewareException} if the specified response is
	 * invalid; if not, returns the response
	 * 
	 * @param  mixed      $response
	 * @param  Middleware $middleware the middleware returning the response
	 * @return Response
	 * @throws CobwebMiddlewareException
	 */
	protected function assertResponse($response, Middleware $middleware) {
		if (!($response instanceof Response))
			throw new CobwebMiddlewareException(
				get_class($middleware) . ' did not return HTTPResponse, got ' . 
				gettype($response));
		return $response;
	}
	
	/**
	 * Loads and instantiates middleware as specified by by the <var>$installed_middleware</var>
	 * parameter or the value of the <var>INSTALLED_MIDDLEWARE</var> setting.
	 * 
	 * @param  array $installed_middleware if NULL, uses <var>INSTALLED_MIDDLEWARE</var>
	 * @return array the loaded middleware objects
	 */
	public function load(array $installed_middleware = NULL) {
		$this->middleware = array();
		
		$installed_middleware = $installed_middleware ? 
			$installed_middleware :
			Cobweb::get('INSTALLED_MIDDLEWARE', array());

		foreach ($installed_middleware as $middleware_name) {
			
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

		return $this->middleware;
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
		
		if (!is_file($path))
			throw new FileNotFoundException(
				"Unable to load middleware '{$middleware_name}' from '$path'");
			
		return $path;
			
	}
	
	public function __toArray() {
		return $this->middleware;
	}
}