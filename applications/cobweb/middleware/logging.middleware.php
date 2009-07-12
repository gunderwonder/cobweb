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
class LoggingMiddleware extends Middleware {
	
	protected $loggers;
	
	public function initialize() {
		$this->loggers = array();
		$this->dispatcher->observe('logging.register_logger', array($this, 'registerLogger'));
	}
	
	public function processResponse(Request $request, Response $response) {
		
		if (in_array($response->code(), array(304)))
			return $response;
		
		if (!Cobweb::get('DEBUG'))
			return $response;
		
		foreach ($this->loggers as $logger) {
			$formatter_class = Cobweb::get('LOG_FORMATTER_CLASSNAME', 'FirebugLogFormatter');
			$formatter = new $formatter_class($logger);
			$formatter->format($response);
		}
		
		return $response;
		
	}
	
	public function registerLogger(CobwebEvent $event) {
		$this->loggers[] = $event->logger;
	}
}