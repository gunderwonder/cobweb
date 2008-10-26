<?php

class CobwebTestSuite extends GroupTest {
	
	public function load($path) {
		$directory = new DirectoryIterator($path);
		foreach ($directory as $file)
			if ($file->isFile() && str_ends_with($file->getPathname(), '.test.php'))
				$this->addTestFile($file->getPathname());
	}
	
}