<?php

abstract class CobwebManagerCommand {
	
	/** @var CobwebManager */
	protected $manager;
	
	/** @var boolean */
	private $requires_project;
	
	/** @var integer */
	private $required_parameter_count;
	
	/** @var string */
	private $usage_string;
	
	public function __construct(CobwebManager $manager,
		                        $command,
		                        array $arguments = array(), 
		                        array $flags = array()) {
			
		$this->command = $command;
		$this->arguments = $arguments;
		$this->flags = $flags;
		$this->manager = $manager;
		
		$this->required_argument_count = 0;
		$this->requires_project = false;
		
		$this->usage_string = '';
		
		$this->initialize();
		$this->configure();
		$this->validate();
		
		
		
	}
	
	protected function validate() {
		if (count($this->arguments) < $this->requiredArgumentCount())
			$this->failWithUsage('Not enough arguments.');
		
		
		if ($this->isProjectCommand()) {
			define('COBWEB_PROJECT_DIRECTORY', getcwd());
			if (!file_exists(COBWEB_PROJECT_DIRECTORY . '/settings/settings.conf.php'))
				$this->fail('Current working directory does not appear to be a Cobweb project directory');
			Cobweb::initialize();
		}
			
	}
	
	protected function failWithUsage($message = '') {
		$usage_string = empty($this->usage_string) ? 
		                '' :
		                "\nUsage: cobweb {$this->name()} {$this->usage_string}";
		$this->fail("{$message}{$usage_string}");
	}
	
	public function initialize() { }
	
	public function configure() { }
	
	public function name() {
		return $this->command;
	}
	
	protected function requiresProject() {
		$this->requires_project = true;
	}
	
	protected function requiresArgumentCount($count) {
		$this->required_argument_count = $count;
	}
	
	public function requiredArgumentCount() {
		return $this->required_argument_count;
	}
	
	protected function usage($usage_string) {
		$this->usage_string = $usage_string;
	}
	
	protected function document($documentation) {
		// return $this->usage_string = $usage_string;
	}
	
	public function isProjectCommand() {
		return $this->requires_project;
	}
	
	public function fail($message) {
		$this->manager->fail("{$this->name()}: $message");
	}
	
	public function info($message) {
		$this->manager->info($message);
	}
	
	public function debug($message) {
		$this->manager->debug($message);
	}
	
	
	public abstract function execute();
	
	
}