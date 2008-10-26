<?php

class Application {
	
	protected 
		$name,
		$path;
	
	public function __construct(Dispatcher $dispatcher, 
		                        Request $request,
		                        $application_name,
		                        $path) {
			
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->name = $application_name;
		$this->path = $path;
		
		$this->initialize();
	}
	
	protected function initialize() {
		if (file_exists($this->path . '/settings/bootstrap.inc.php'))
			require_once $this->path . '/settings/bootstrap.inc.php';
	}
	
	public function path() {
		return $this->path;
	}
}