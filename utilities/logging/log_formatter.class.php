<?php

abstract class LogFormatter {
	
	protected $logger;
	
	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}
	
	abstract public function format();
	
}