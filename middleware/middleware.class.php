<?php
/** @version $Id$ */

/**
 * Abstract base class for {@link Middleware}. 
 * 
 * The middleware system provides hooks into Cobweb's request-response
 * cycle. If a middleware is specified in the setting `INSTALLED_MIDDLEWARE' it
 * is intantated upon request and invoked for each stage in the request-response
 * cycle: 
 * 
 * 1. When a request is made ({@link Middleware::processRequest()})
 * 2. Before an action is invoked ({@link Middleware::processAction()})
 * 3. Before a response is flushed ({@link Middleware::processResponse()})
 * 4. If an exception is thrown ({@link Middleware::processException()})
 * 
 * A middleware implementation needs to override one or more of these methods
 * and may e
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Middleware
 * @version    $Revision$
 */
abstract class Middleware implements RequestProcessor {
	
	/** @var Dispatcher */
	protected $dispatcher;
	
	/**
	 * Intantiates a middleware object.
	 * 
	 * Middleware instantation is handle by Cobwebs middleware framework. 
	 * There is no need to call this constructor directly.
	 * 
	 * @param  Dispatcher $dispatcher
	 * @return Middleware
	 */
	public function __construct(Dispatcher $dispatcher) {
		$this->dispatcher = $dispatcher;
		$this->initialize();
	}
	
	/**
	 * Called when the middleware object is instantiated.
	 * 
	 * Override this method to supply intitialization code -- overriding
	 * the constructor is not allowed unless it's compatible with 
	 * {@link Middleware::construct()}.
	 */
	public function initialize() { }
	
	public function processRequest(Request $request) {
		return NULL;
	}
	
	public function processResponse(Request $request, Response $response) {
		return $response;
	}
	
	public function processAction(Request $request, Action $action) {
		return NULL;
	}
	
	public function processException(Request $request, Exception $exception) {
		return NULL;
	}
	
}