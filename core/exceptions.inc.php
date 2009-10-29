<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 * @package Cobweb
 * @subpackage Core
 */

class NotImplementedException extends RuntimeException { }

class SecurityException extends RuntimeException { }
class AccessControlException extends SecurityException { }

class IOException extends RuntimeException { }
class FileNotFoundException extends IOException { }

class CobwebException extends RuntimeException { }
class CobwebErrorException extends ErrorException {
	
	/** @var string */
	private $error_message;
	
	/** @var array */
	private $trace = NULL;
	
	public function __construct(
				$error_message,
				$error_code,
				$error_number,
				$error_file,
				$error_line_number,
				$trace = NULL) {
					
		
		$this->trace = is_null($trace) ? array_slice(debug_backtrace(), 2) : $trace;
		
		$this->error_message = $error_message;
		parent::__construct(
			$error_message, 
			0,
			$error_number, 
			 
			$error_file, 
			$error_line_number);
	}
	
	public function origin() {
		return $this->trace;
	}

}

class CobwebConfigurationException extends CobwebException { }
class CobwebMiddlewareException extends CobwebException { }

class HTTPException extends CobwebException { }

class HTTP404 extends HTTPException { 
	public function __construct() {
		$href = Cobweb::get('__REQUEST__')->URI();
		parent::__construct('Page not found: ' . $href, 404);
	}
}

class HTTP500 extends HTTPException { 
	public function __construct($message = '') {
		$message = ($message == '' ? '' : ': ' . $message);
		parent::__construct("Internal server error{$message}", 500);
	}
}

class HeadersSentException extends HTTPException {
	public function __construct($message = '') {
		assert(headers_sent($file, $line));
		parent::__construct($message . " Output started in $file on line $line", 500);
	}
}

class CobwebDispatchException extends CobwebException { }

class HTTPClientException extends RuntimeException { }