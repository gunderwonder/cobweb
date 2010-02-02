<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
final class CobwebMiddleware extends Middleware {
	private $error = false;
	
	private $middleware_manager = NULL;
	
	public function processAction(Request $request, Action $action) {
		
		$request_processors = array();
		foreach ($action->allAnnotations() as $annotation) {
			if ($annotation instanceof RequestProcessor) {
				$request_processors[] = $annotation->prepare($this->dispatcher);
			}
		}
		
		if (empty($request_processors))
			return NULL;
			
		$this->middleware_manager = new MiddlewareManager(
			$this->dispatcher, 
			Cobweb::instance()->applicationManager(), 
			$request_processors
		);
		
		if ($middleware_response = $this->middleware_manager->handleRequest($request))
			return $this->middleware_manager->handleResponse($request, $middleware_response);
		
		if ($middleware_response = $this->middleware_manager->handleAction($request, $action))
			return $this->middleware_manager->handleResponse($request, $middleware_response);
	}
	
	public function processResponse(Request $request, Response $response) {
		if ($this->middleware_manager && 
			$middleware_response = $this->middleware_manager->handleResponse($request, $response))
			return $this->middleware_manager->handleResponse($request, $middleware_response);
		
		return $response;
	}
	
	public function processException(Request $request, Exception $e) {
		
		if ($this->error)
			return NULL;
		$this->error = true;
		
		if ($this->middleware_manager &&
			$middleware_response = $this->middleware_manager->handleException($request, $e))
			return $this->middleware_manager->handleResponse($request, $middleware_response);

		if (Cobweb::get('DEBUG'))
			return Controller::invoke('cobweb.debug.debugger', array('exception' => $e));
		else if ($e instanceof HTTP404)
			return Controller::invoke(Cobweb::get('404_ACTION', 'cobweb.cobweb.not_found_404'));
		else {
			if (Cobweb::get('SEND_EMAIL_ON_500', true))
				$this->sendExceptionEmail($e, $request, Cobweb::get('ADMINISTRATORS', array()));
			
			return Controller::invoke('cobweb.cobweb.graceful_exception', array('exception' => $e));	
		}		
	}
	
	protected function sendExceptionEmail($exception, $request, $administrators) {
		$now = date_format(new DateTime(), DATE_RFC850);

		$POST = stringify($request->POST->toArray());
		$GET = stringify($request->GET->toArray());
		
		$message = <<<EOS
Host: {$request->host()}
Time: $now

Request method: {$request->method()}
GET: {$GET}
POST: {$POST}

Exception message: {$exception->getMessage()}
Exception backtrace:
{$exception->getTraceAsString()}
EOS;
		$exception_class = get_class($exception);		
		$subject = "{$request->host()}: $exception_class caught";
		$to = '';
		foreach ($administrators as $name => $address)
			$to .= $name ? "{$name} <{$address}>, " : "{$address}, ";
		trim($to, ',');
		
		
		if ($to)
			try {
				mail(trim($to, ','), $subject, $message);
			} catch (Exception $e) { }
	}
	

}