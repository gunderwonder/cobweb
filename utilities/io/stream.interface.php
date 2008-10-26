<?php

interface Stream {
	
	public function write($string);
	public function read($size = NULL);
	public function truncate($size);
	public function seek($offset);
	public function close();
	public function tell();
	public function writelines(array $sequence);
           
	public function readline();
	public function readlines();
	       
	public function file();
	public function flush();
	
	public function mode();
	
}