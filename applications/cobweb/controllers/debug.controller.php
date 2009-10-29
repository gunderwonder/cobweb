<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

// THIS CODE NEEDS SERIOUS CLEANUP...

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Cobweb Application
 * @version    $Revision$
 */
class DebugController extends Controller {
	
	/** 
	 * @param   Exception     $exception  the thown exception
	 * @return  HTTPResponse  500/404 response with detailed stacktrace/debug information
	 */
	public function debugger(Exception $exception) {
	    
		if ($this->request->isAJAX())
			return $this->textDebugger($exception);
		
		$template = new Template();
		
		$template['exception_class'] = get_class($exception);
		$template['e'] = $exception;
		
		$template['file_path'] = lstrip($exception->getFile(), COBWEB_PROJECT_DIRECTORY);
	
		$backtrace = method_exists($exception, 'origin') ? $exception->origin() :  $exception->getTrace();

		foreach ($backtrace as $i => &$trace) {
			$trace['base_filename'] = isset($trace['file']) ? basename($trace['file']) : NULL;
			
			if (!isset($trace['function'])) {
				// $trace['named_args'] = array();
			
			// builtin function
			} else if (in_array($trace['function'], 
			           array('require_once', 'require', 'include', 'include_once'))) {
				// $trace['named_args'] = array();
				
			// function
			} else if (!isset($trace['class'])) {
				$function = new ReflectionFunction($trace['function']);
				$this->functionInformation($function, $trace);
				
			// method
			} else {
				if ($trace['class'] == 'ReflectionMethod')
					;
				else {
					
					/**
					 * oddly, PHP does not care about method calls whose
					 * methods are invalid (methods with names that contain e.g. `include` )
					 */
					try {
						$reflector = new ReflectionClass($trace['class']);
						$method = $reflector->getMethod($trace['function']);
						$this->functionInformation($method, $trace);
					} catch (Exception $e) {
						
					}
				}
				
				if (str_ends_with($trace['class'], 'Controller'))
					$trace['is_controller'] = true;
					
			}

			$trace['stack_line'] = isset($backtrace[$i - 1]['line']) ? $backtrace[$i - 1]['line'] : $exception->getLine();
		}

		$template['response_headers'] = $this->responseHeaders();
		$template['backtrace'] = $backtrace;
		
		$template['attempted_patterns'] = $template['matching_pattern'] = NULL;
		$resolver = Cobweb::instance()->resolver();
		if (method_exists($resolver, 'attemptedPatterns'))
			$template['attempted_patterns'] = $resolver ? $resolver->attemptedPatterns() : array();
		if (method_exists($resolver, 'matchingPattern'))
			$template['matching_pattern'] = $resolver ? $resolver->matchingPattern() : array();
		
		$template->render(
			COBWEB_DIRECTORY . '/applications/cobweb/templates/debug/exception.tpl', 
			Template::ABSOLUTE_TEMPLATE_PATH);
		
		
		while (ob_get_level())
            @ob_get_clean();
		
		
		$code = $exception instanceof HTTPException ? $exception->getCode() : 500;

		return new HTTPResponse($template, $code);
	}
	
	/**
	 * Adds an array of named parameters of the specified function and 
	 * their values to the specified backtrace.
	 * 
	 * @param ReflectionFunctionAbstract $function function/method to analyze
	 * @param array                      $trace    backtrace from {@link debug_backtrace()}
	 */
	private function functionInformation(ReflectionFunctionAbstract $function, array &$trace) {
		$named_parameters = array();
		$parameters = $function->getParameters();
		
		$i = 0;
		foreach ($parameters as $parameter) {
			if (isset($trace['args'][$i])) {
				
				$name = $parameter->getName();
				$value = $trace['args'][$i];
				$named_parameters[$name]['type'] = gettype($value);
				$named_parameters[$name]['value'] = is_object($value) ? 
					get_class($value) : 
					$value;
				try {
					$named_parameters[$name]['json'] = JSON::debug($value);
				} catch (ErrorException $e) {
					$named_parameters[$name]['json'] = '';
				}
			}
				
			$i++;
		}
		
		$source = $this->source($function);
		if ($source) {
			$trace['comments'] = $this->trimDocComments($function->getDocComment());
			$trace['source'] = $source;
			$trace['source_range'] = range($function->getStartLine(), $function->getEndLine());
		}
		
		$trace['named_args'] = $named_parameters;
	}
	
	
	// returns the source of the specified function
	private function source(ReflectionFunctionAbstract $function) {
		return read_file_lines($function->getFileName(), $function->getStartLine(), $function->getEndLine());
	}
	
	// trims leading tabs (soft or hard) from a doctring
	private function trimDocComments($comments) {
		return preg_replace('/^\t| {4}/m', '', $comments);
	}
	
	// returns a pretty-printed array of response headers
	private function responseHeaders() {
		if (function_exists('apache_response_headers'))
			return apache_response_headers();
			
		$headers = array();
		foreach(headers_list() as $h) {
			list($header_name, $value) = preg_split('/:\s/', $h);
			$headers[$header_name] = $value;
		}
		
		return $headers;
	}
	
	private function textDebugger(Exception $e) {
		$code = $e instanceof HTTPException ? $e->getCode() : 500;
		
		$template = new Template();
		$template->bind(array('exception' => $e, 'type' => get_class($e)));
		$template->render(
			COBWEB_DIRECTORY . '/applications/cobweb/templates/debug/text_exception.tpl', 
			Template::ABSOLUTE_TEMPLATE_PATH
		);
		
		return $this->respond(
			$template,
			$code,
			MIMEType::TEXT
		);
	}
}

function read_file_lines($file, $from, $to) {
	if (!$file || !($lines = file($file))) return '';
	return implode('', array_slice($lines, $from - 1, $to - $from + 1));
}

?>