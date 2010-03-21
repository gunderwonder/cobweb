<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Logging
 */
class LoggingDisabled extends Annotation { }

/**
 * @package Cobweb
 * @subpackage Logging
 */
class LoggingEnabled extends Annotation { }

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Logging
 */
class LoggingMiddleware extends Middleware {
	
	protected $response_processed = false;
	
	protected $loggers;
	protected $logging_enabled = NULL;
	
	public function initialize() {
		$this->loggers = array();
		$this->dispatcher->observe('logging.register_logger', array($this, 'registerLogger'));
	}
	
	public function processAction(Request $request, Action $action) {
		if ($action->hasAnnotation('LoggingEnabled'))
			$this->logging_enabled = true;
		else if ($action->hasAnnotation('LoggingDisabled'))
			$this->logging_enabled = false;
			
		$this->logging_enabled = is_null($this->logging_enabled) ?
			Cobweb::get('DEBUG') :
			$this->logging_enabled;
	}
	
	public function processResponse(Request $request, Response $response) {
		if ($this->response_processed)
			return $response;
		
		if (in_array($response->code(), array(304)))
			return $response;
		
		if (!$this->logging_enabled)
			return $response;
		
		$formatter_class = Cobweb::get('LOG_FORMATTER', 'FirebugLogFormatter');
		foreach ($this->loggers as $logger) {
			$formatter = new $formatter_class($logger);
			$formatter->format($response);
		}
		
		$this->response_processed = true;
		return $response;
		
	}
	
	public function registerLogger(CobwebEvent $event) {
		$this->loggers[] = $event->logger;
	}
}