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
	private static $current_stacktrace = NULL;
	
	public function __construct(
				$error_number,
				$error_message,
				$error_file,
				$error_line_number,
				$error_context,
				$trace = NULL) {
					
		
		self::$current_stacktrace = is_null($trace) ? array_slice(debug_backtrace(), 2) : $trace;
		$this->rethrow($error_message);
		
		$this->error_message = $error_message;
		parent::__construct(
			$error_message, 
			$error_number, 
			0, 
			$error_file, 
			$error_line_number);
	}
	
	public static function currentStacktrace() {
		$stacktrace = self::$current_stacktrace;
		self::$current_stacktrace = NULL;
		return $stacktrace;
	}
	
	public function rethrow($message) {
		if (preg_match('/^Undefined variable: \w+$/', $message))
			throw new UnexpectedValueException($message);
			
		if (preg_match('/^Undefined offset: /', $message))
			throw new OutOfBoundsException($message);
			
		// TODO: add more Exception types here...
	}
	
	public function rethrowedExceptions() {
		return array('UnexpectedValueException', 'OutOfBoundsException');
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