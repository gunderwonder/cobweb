<?php

class LoggingMiddleware extends Middleware {
	
	protected $loggers;
	
	public function initialize() {
		$this->loggers = array();
		$this->dispatcher->observe('logging.register_logger', array($this, 'registerLogger'));
	}
	
	public function processResponse(Request $request, Response $response) {
		
		$formatted_logs = '';
		foreach ($this->loggers as $logger) {
			$formatter = new FirebugLogFormatter($logger);
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
			
			
		// JSON
		} else if ($request instanceof AJAXResponse) {
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