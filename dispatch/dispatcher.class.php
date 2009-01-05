<?php
/**
 * @version $Id$ 
 */

/**
 * Cobweb's central request dispatch hub
 *
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Dispatch
 * @version    $Revision$
 */
class Dispatcher {
	
	protected 
		/** @var Request */
		$request,
		
		/** @var array */
		$event_listeners;
	
	
	public function __construct() {
		$this->event_listeners = array();
	}
	
	public function dispatch(Request $request, Action $action, MiddlewareManager $middleware) {
		$response = NULL;
		
		// pass request through middleware...
		if ($middleware_response = $middleware->handleRequest($request))
			return $middleware->handleResponse($this->request, $middleware_response);
		
		// pass action through middleware...	
		if ($middleware_response = $middleware->handleAction($request, $action))
			return $middleware->handleResponse($request, $middleware_response);
		
		// invoke the action, catching any exception it may throw
		try {
			$response = $action->invoke();
		
		/* if an exception is thrown in the controller layer, let the middleware
		 * handle it or rethrow the exception
		 */
		} catch (Exception $exception) {
			
			Cobweb::error('Caught exception! %o', $exception);
		
			if ($middleware_response = $middleware->handleException($request, $exception)) {
				Cobweb::info('Sending response: %o', $middleware_response);
				return $middleware->handleResponse($request, $middleware_response);
			}
				
			throw $exception;
		}

		Cobweb::info('Cobweb ran for %o seconds', 
		             microtime(true) - Cobweb::get('__COBWEB_START_TIME__'));
		
		// finally, return the response processed by the middleware
		return $middleware->handleResponse($request, $response);
	}
	
	public function finalize(Response $response) {
		$response->flush();
	}
	
	/**
	 * Adds an event listener to the specified event.
	 *  
	 * @param string $event_name  the label of the event to listen for
	 * @param mixed  $callable    callback to invoke when the event is fired
	 */
	public function observe($event_name, $callable) {
		if (!isset($this->event_listeners[$event_name]))
			$this->event_listeners[$event_name] = array();
			
		$this->event_listeners[$event_name] = $callable;
	}
	
	/**
	 * Fires an event specified by its label.
	 * 
	 * The caller may pass in an optional array key-value pairs, a "memo", 
	 * that is made available to the event listener callback.
	 * 
	 * @param  string  $event_name  the label of the event to fire
	 * @param  array   $memo        a memo to pass to the event listener callback
	 */
	public function fire($event_name, array $memo = NULL) {
		if (!isset($this->event_listeners[$event_name]))
			return;
		
		$event = new CobwebEvent($event_name, $memo ? $memo : array());
		foreach ($this->event_listeners as $callable)
			if (!$event->isStopped())
				call_user_func_array($callable, array($event));
				
		return $event;
	}
	
}

