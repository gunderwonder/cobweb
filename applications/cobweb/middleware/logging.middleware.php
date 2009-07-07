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
		
		if (!Cobweb::get('DEBUG'))
			return $response;
		
		$formatted_logs = '';
		foreach ($this->loggers as $logger) {
			$formatter_class = Cobweb::get('LOG_FORMATTER_CLASSNAME', 'FirebugLogFormatter');
			$formatter = new $formatter_class($logger);
			$formatted_logs .= $formatter->format();
		}
		
		if ($response->code() == 304)
			return $response;
		
		// HTML
		if ($response['Content-Type'] == MIMEType::HTML) {
			
				if (($position = utf8_strpos($response->body, '</head>')) !== false)
					$response->body = str_replace(
						'</head>', 
						$formatted_logs . "\n</head>", 
						$response->body
					);
				else
					$response->body .= $formatted_logs;
			
		// AJAXResponse
		} else if ($response instanceof AJAXResponse) {
			$json = JSON::decode($response->body);
			$json['logs'] = $formatted_logs;
			$response->body = JSON::encode($json);
		}

		return $response;
		
	}
	
	public function registerLogger(CobwebEvent $event) {
		$this->loggers[] = $event->logger;
	}
}