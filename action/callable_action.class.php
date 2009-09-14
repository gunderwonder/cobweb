<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Using this annotation will cause the callable to not be exposed to the
 * action.
 * 
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Dispatch
 */
class Concealed extends Annotation { }

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Dispatch
 */
class CallableAction implements Action {
	
	protected $callable;
	protected $options;
	protected $arguments;
	protected $pattern;
	protected $resolver;
	
	public function __construct(HTTPRequest $request,
		                        Dispatcher $dispatcher,
		                        Resolver $resolver,
		                        $pattern,
		                        $arguments,
		                        array $specification) {
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->pattern = $pattern;
		$this->resolver = $resolver;

		
		$this->initialize($specification, $arguments);
		$this->loadAction();
	}
	
	/**
	 * Returns the URL pattern that resolved to this action
	 * @return string the URL pattern
	 */
	public function matchingPattern() {
		return $this->pattern;
	}
	
	/**
	 * Returns the arguments (if any) specified in the URL configuration file
	 * @
	 */
	public function arguments() {
		return $this->arguments;
	}
	
	public function invoke(array $arguments = NULL) {
		$arguments = is_null($arguments) ? $this->arguments() : $arguments;
		$arguments = $this->validateArguments($arguments);
		
		$response = call_user_func_array($this->callable, $arguments);
		if (!($response instanceof Response))
			throw new CobwebDispatchException('Callable action did not return a `Response` instance!');
			
		return $response;
	}
	
	protected function validateArguments($argument_values) {
		
		$required_parameter_count = $this->reflection()->getNumberOfRequiredParameters();
		$parameter_count = $this->reflection()->getNumberOfParameters();
		$arguments = array();
		$parameters = $this->reflection()->getParameters();
		for ($i = 0; $i < count($parameters); $i++) {
			$parameter = $parameters[$i];
			
			// named argument
			if (isset($argument_values[$parameter->getName()]))
				$arguments[$i] = $argument_values[$parameter->getName()];
			
			// positional argument
			else if (isset($argument_values[$i]))
				$arguments[$i] = $argument_values[$i];
				
			// use default when present
			else if ($parameter->isDefaultValueAvailable())
				$arguments[$i] = $parameter->getDefaultValue();
					
			// missing required argument throws exception
			else
				throw new CobwebDispatchException(
					"Error invoking '{$this->reflection()->getName()}()' " .
					"for URL '{$this->request->path()}'. " . 
					"Required parameter '{$parameter->getName()}' is not specified");
		}
		return $arguments;
	}
	
	
	protected function initialize($specification, $arguments) {
		
		$this->callable = $specification[0];
		
		array_shift($specification);
		$ending = end($specification);
		$key    = key($specification);
		if (is_numeric($key) && is_array($ending)) {
			$this->options = $ending;
			array_pop($specification);
		} else
			$this->options = array();
		
		$arguments = array_merge($specification, $arguments);
		array_unshift($arguments, $this->request);

		$this->arguments = $this->validateArguments($arguments);
		
	}
	
	public function options() {
		return $this->options;
	}
	
	protected function loadAction() { }
	
	protected function reflection() {
		if (is_string($this->callable)) {
			if (str_contains($this->callable, '::')) {
				list($class, $method) = explode('::', $this->callable);
				$class = new ReflectionAnnotatedClass($class);
				return $class->getMethod($method);
			}
			
			return new ReflectionFunction($this->callable);
		} else {
			list($object_or_class, $method) = $this->callable;
			if (is_string($object_or_class))
				$object_or_class = new ReflectionAnnotatedClass($object_or_class);			
			return $object_or_class->getMethod($method);
		}
	}
	
	/**
	 * Factory method to create the callable action
	 * @return CallableAction
	 */
	public static function create(Request $request,
		                          Dispatcher $dispatcher,
		                          Resolver $resolver,
		                          $pattern,
		                          $arguments,
		                          array $specification) {
		if (empty($specification))
			throw new CobwebDispatchException('Invalid callable action specification');
		
		$action = $specification[0];
		if (is_callable($action))
			return new CallableAction($request, $dispatcher, $resolver, $pattern, $arguments, $specification);
		else
			return new ControllerAction($request, $dispatcher, $resolver, $pattern, $arguments, $specification);
	}
	
	
	/**
	 * @see @Action::hasAnnotation
	 */
	public function hasAnnotation($annotation) {
		return $this->reflection()->hasAnnotation($annotation);
	}
	
	/**
	 * @see @Action::annotation
	 */
	public function annotation($annotation) {
		if ($this->reflection()->hasAnnotation($annotation))
			return $this->reflection()->getAnnotation($annotation);
	}
	
	public function allAnnotations() {
		return $this->reflection()->allAnnotations();
	}
	
	public function __toString() {
		return $this->callable;
	}
}