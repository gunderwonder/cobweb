<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


/**
 * Represents an {@link Action} that invokes a {@link Controller} action.
 * 
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Dispatch
 * @version    $Revision$
 */
class ControllerAction extends CallableAction {
	
	private 
		$controller_name = NULL,
		$controller      = NULL,
		$action_name     = NULL,
		$instance        = NULL;
	
	protected function initialize($specification, $arguments) {
		
		$this->action_label = '';
		if (is_string($specification))
			$this->action_label = $options;
		else if (!is_array($specification) || empty($specification))
			throw new CobwebConfigurationException(
				'Invalid controller action specification ', stringify($options));
		else
			$this->action_label = $specification[0];
		
		// get the application, controller and action
		if (count(@list($this->application_name,
			           $this->controller_name,
			           $this->action_name) = 
			      explode('.', $this->action_label)) != 3)
			throw new CobwebConfigurationException(
				'Invalid controller action label ' . stringify($this->action_label));
		
		// check if the application is installed
		if (!in_array($this->application_name, Cobweb::get('INSTALLED_APPLICATIONS')))
			throw new CobwebConfigurationException(
				"The application '{$this->application_name}' is not in your 'INSTALLED_APPLICATIONS'.");
		
		// no options here...
		if (!is_array($specification)) {
			$this->options = array();
			$this->arguments = $arguments;
			return;
		}
		
		// options if present (oh, god, this is some hairy code...)
		array_shift($specification);
		$ending = end($specification);
		$key    = key($specification);
		if (is_numeric($key) && is_array($ending)) {
			$this->options = $ending;
			array_pop($specification);
		} else
			$this->options = array();
		
		$this->arguments = array_merge($specification, $arguments);
	}
	
	protected function loadAction() {
		Cobweb::loadApplication($this->application_name);
		
		$label = $this->controller_name;
		
		if (!class_exists($this->controller_name) || !is_subclass_of($this->controller_name, 'Controller')) {
			$this->controller_name = str_classify($this->controller_name) . 'Controller';	
			$this->loadControllerFile($label);
		}
		
		if (!class_exists($this->controller_name)) {
			throw new CobwebConfigurationException(
				"Unable to load controller {$this->controller_name} ".
				"(from label {$this->action_label})");	
		}	
			
		$this->controller = new ReflectionAnnotatedClass($this->controller_name);
		
		if (!$this->controller->isSubclassOf(new ReflectionClass('Controller')))
			throw new CobwebConfigurationException(
				"Controller class '{$this->controller_name}' is not an instance of 'Controller'");
		
		if (!$this->controller->hasMethod($this->action_name))
			$this->action_name = str_camelize($this->action_name);

		if (!$this->controller->hasMethod($this->action_name))
			throw new CobwebConfigurationException(
				"Controller class '{$this->controller_name}' doesn't implement '{$this->action_name}()'");
				
		if ($this->controller->isAbstract())
			throw new CobwebConfigurationException(
				"Controller class '{$this->controller_name}' is abstract and cannot be instantiated.'");
			
		$this->action = $this->controller->getMethod($this->action_name);
		
		if (!$this->action->isPublic())
			throw new CobwebDispatchException(
				"{$this->controller_name}::{$this->action_name}' is not a public method");
	}
	
	public function invoke(array $arguments = NULL) {
		$arguments = is_null($arguments) ? $this->arguments : $arguments;
		$arguments = $this->validateArguments($arguments);
		
		$response = NULL;
		try {
			$class = $this->controller->getName();
			$method = $this->action->getName();
			
			$this->instance = $this->controller->newInstance($this->dispatcher, $this->request, $this->resolver);
			
			if(($response = $this->controller->getMethod('processRequest')->invoke($this->instance, $this->request)))
				return $response;
			if(($response = $this->controller->getMethod('processAction')->invoke($this->instance, $this->request, $this)))
				return $response;
			
			Cobweb::info('Invoking %o with arguments %o', "{$class}::{$method}", $arguments);
			$response = $this->action->invokeArgs($this->instance, $arguments);
			if (!$response instanceof Request)
				throw new CobwebException(
					"Action '{$class}::{$method}' must return a response instance, got " .
					(is_object($response) ? get_class($response) . ' instance' : gettype($response))
				);
			
			$response = $this->controller->getMethod('processResponse')->invoke($this->instance, $this->request, $response);
	
		} catch (ReflectionException $e) { 
			throw new CobwebDispatchException(
				"Error invoking '{$this->action->getName()}' in controller " .
				"{$this->controller->getName()} for URL '{$this->request->path()}'. " . 
				"The error was: '{$e->getMessage()}'");
		}
	
		return $response;
	}
	
	public function controllerInstance() {
		return $this->instance;
	}
	

	private function loadControllerFile($controller_label) {
	
		foreach (Cobweb::get('APPLICATIONS_PATH') as $path) {
			$file = "{$path}/{$this->application_name}/controllers" .
			        "/{$controller_label}.controller.php";
			if (file_exists($file)) {
				require_once($file);
				return;
			}
		}
		
		throw new FileNotFoundException(
			"Could not find file containing controller {$this->controller_name}");
	}
	
	public function controller() {
		return $this->controller;
	}
	
	public function reflection() {
		return $this->action;
	}
	
	public function __toString() {
		return "{$this->controller()->getName()}::{$this->reflection()->getName()}";
	}
	
	public function hasAnnotation($annotation) {
		return $this->controller()->hasAnnotation($annotation) ||
		       $this->reflection()->hasAnnotation($annotation);
	}
	
	public function annotation($annotation) {
		if ($this->reflection()->hasAnnotation($annotation))
			return $this->reflection()->getAnnotation($annotation);
			
		if ($this->controller()->hasAnnotation($annotation))
			return $this->controller()->getAnnotation($annotation);
		return NULL;
	}
	
	public static function invokeControllerAction($label, array $arguments = array()) {
		
		$action = new ControllerAction(
			Cobweb::get('__REQUEST__'),
		    Cobweb::get('__DISPATCHER__'),
		    Cobweb::get('__RESOLVER__'),
		    '',
		    $arguments,
		    array_merge(array($label), $arguments)
		);
		
		return Cobweb::get('__DISPATCHER__')->dispatch(
			Cobweb::get('__REQUEST__'),
			$action,
			Cobweb::get('__MIDDLEWARE_MANAGER__')
		);
	}
}