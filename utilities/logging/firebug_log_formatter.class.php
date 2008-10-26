<?php

class FirebugLogFormatter extends LogFormatter {
	
	
	public function format() {
		if ($this->logger->isEmpty())
			return '';
		
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
				return;
			}
		
			for ($i = 0; $i < count($things); $i++) {
				if ($i != 0) 
					$message .= ',' . JSON::encode($things[$i]);
				else
					$message .= JSON::encode($things[$i]);
			}
				
        	
			$log .= $message . ");\n";
			
				
		}
		return $log . "\t\tconsole.groupEnd()\n\t}\n\t// ]]>\n</script>";
	}
	
	
}