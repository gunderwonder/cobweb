<?php


final class ConcreteForm extends Form {
	
	public function __construct(array $specification, $data = NULL) {
		foreach ($specification as $key => $field) {
			$this->__set($key, $field);
		}
			
			
		parent::__construct($data);
	}
	
	public function configure() {
		
	}
	
	
}