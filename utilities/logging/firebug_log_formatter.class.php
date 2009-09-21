<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class FirebugLogFormatter extends LogFormatter {
	
	
	public function format(Response $response) {
		if ($this->logger->isEmpty())
			return;
		
		$log = "<script type=\"text/javascript\" charset=\"utf-8\">\n" .
			"\t// <![CDATA[\n" .
			"\tif (typeof console != 'undefined') {\n" . 
			"\t\tif (typeof console.groupCollapsed != 'function')\n" . 
			"\t\t\tconsole.groupCollapsed = console.group;\n" . 

			"\t\tconsole.groupCollapsed('{$this->logger->name()}');\n";
		foreach ($this->logger as $invocation) {

			$message = "\t\tconsole.{$invocation[0]}(";
			$things = $invocation[1];
			if (!is_array($things) || count($things) == 0) {
				$message .= "null);\n";
			}
		
			for ($i = 0; $i < count($things); $i++) {
				if ($i != 0) 
					$message .= ',' . JSON::debug($things[$i]);
				else
					$message .= JSON::debug($things[$i]);
			}
				
        	
			$log .= $message . ");\n";
		}
		$log .= "\t\tconsole.groupEnd()\n\t}\n\t// ]]>\n</script>";
		
		// HTML
		if ($response->contentType() == MIMEType::HTML) {
			
				if (($position = utf8_strpos($response->body, '</head>')) !== false)
					$response->body = str_replace(
						'</head>', 
						$log . "\n</head>", 
						$response->body
					);
				else
					$response->body .= $log;
			
		// AJAXResponse
		} else if ($response instanceof AJAXResponse) {
			$response->body['log'] = $log;
		}
	}
	
	
}