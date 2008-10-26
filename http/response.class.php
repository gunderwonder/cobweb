<?php

abstract class Response implements ArrayAccess {
	
	public $body;
	
	abstract public function write($contents);
	abstract public function flush();
	abstract public function code();
}