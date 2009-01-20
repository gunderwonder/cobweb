<?php

class FileNotFoundException extends Exception { }

class CobwebException extends Exception { }
class CobwebErrorException extends ErrorException {
	
	/** @var string */
	private $error_message;
	
	public function __construct(
				$error_number,
				$error_message,
				$error_file,
				$error_line_number,
				$error_context,
				$trace = NULL) {
					
		$this->trace = is_null($trace) ? array_slice(debug_backtrace(), 2) : $trace;
		$this->rethrow($error_message);
		$this->error_message = $error_message;
		parent::__construct(
			$error_message, 
			$error_number, 
			0, 
			$error_file, 
			$error_line_number);
	}
	
	public function context() {
		return $this->trace;
	}
	
	public function rethrow($message) {
		if (preg_match('/^Undefined variable: \w+$/', $message))
			throw new UnexpectedValueException($message);
			
		if (preg_match('/^Undefined offset: /', $message))
			throw new OutOfBoundsException($message);
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
		parent::__construct('Internal server error' . $message, 500);
	}
}

class HeadersSentException extends HTTPException { }

class CobwebDispatchException extends CobwebException { }

class HTTPClientException extends RuntimeException { }