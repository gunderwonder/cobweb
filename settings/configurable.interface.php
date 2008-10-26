<?php

interface Configurable {
	
	public function get($key, $default_value = NULL);
	public function set($key, $value);
	       
	public function configure(array $settings);
	
	public function load($file = NULL);
	
}