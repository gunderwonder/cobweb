<?php


class File implements Stream {
	
	const READ = 'r';
	
	private 
		$file,
		$path;
	
	public function __construct($file_path, $mode = self::READ) {
		$this->path = realpath($file_path);
		
		if (!file_exists($this->path))
			throw new FileNotFoundException("'{$this->path}' does not exist");
		$this->file = NULL;
	}
	
	public function open() {
		if (!$this->file)
			$this->file = fopen($this->path, $mode);
	}
	
	public function write($string) {
		
	}
	
	public function read($size = NULL) {
		
	}
	public function truncate($size) {
		
	}
	public function seek($offset) {
		
	}
	public function close() {
		fclose($this->file());
	}
	
	public function tell() {
		
	}
	
	public function writelines(array $sequence) {
		
	}
           
	public function readline() {
		
	}
	
	public function readlines() {
		$contents = file_get_contents($this->path);
		return explode("\n", $contents);
	}
	       
	public function file() {
		return $this->file;
	}
	
	public function flush() {
		return file_get_contents($this->path);
	}
	
	public function name() {
		return basename($this->path);
	}
	
	public function path() {
		return realpath($this->path);
	}
	
	public function isDirectory() {
		return filetype($this->file) == 'dir';
	}
	
	public function isLink() {
		return filetype($this->file) == 'link';
	}
	
	public function isFile() {
		return filetype($this->file) == 'file';
	}
	
	public function isWritable() {
		
	}
	
	public function isReadable() {
		
	}
	
	public function isExecutable() {
		
	}
	
	public function mode() {
		
	}
}


