<?php

class LoggingMiddleware extends Middleware {
	
	protected $loggers;
	
	public function initialize() {
		$this->loggers = array();
		$this->dispatcher->observe('logging.register_logger', array($this, 'registerLogger'));
	}
	
	public function processResponse(Request $request, Response $response) {
		
		if ($response['Content-Type'] == MIMEType::HTML && $response->code() != 304) {
			
			foreach ($this->loggers as $logger) {
				$formatter = new FirebugLogFormatter($logger);
				$logs = $formatter->format();

				// insert log javascript in header
				if (($position = utf8_strpos($response->body, '</head>')) !== false)
					$response->body = str_replace('</head>', $logs . "\n</head>", $response->body);
				else
					$response->body .= $logs;
			}
			
			
			
		} else if ($response['Content-Type'] == MIMEType::JSON) {
			$r = JSON::decode($response->body);
			$r['logs'] = $logs;
			$response->body = JSON::encode($r);
		}

		return $response;
		
	}
	
	public function registerLogger(CobwebEvent $event) {
		$this->loggers[] = $event->logger;
	}
}