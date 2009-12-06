<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP Utilities
 * @version    $Revision$
 */
class UploadedFilesArray extends ImmutableArray {
	
	public function __construct($files) {
		parent::__construct($files);	
	}
	
	/**
	 * Lazy load {@link UploadedFile} objects
	 */
	public function offsetGet($key) {
		if (!isset($this->array[$key]))
			throw new OutOfBoundsException("No uploaded file with key '$key'");
		
		if ($this->array[$key] instanceof UploadedFile)
			return $this->array[$key];
		$this->array[$key] = new UploadedFile($this->array[$key]);
		return $this->array[$key];
		
	}
	
}

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP Utilities
 * @version    $Revision$
 */
class UploadedFile extends SplFileInfo {
	
	public function __construct($file) {
		$this->file = $file;
		parent::__construct($this->file['tmp_name']);
	}
	
	public function MIMEtype() {
		return $this->file['type'];
	}
	
	public function exists() {
		return $this->file['error'] != UPLOAD_ERR_NO_FILE;
	}
	
	public function hasErrors() {
		return $this->file['error'] != UPLOAD_ERR_OK;
	}
	
	public function move($to) {
		if (!$this->exists())
			throw new IOException("Uploaded file {$this->file['name']} does not exist!");
		
		$dirname = dirname($to);
		if (!is_writable($to))
			throw new AccessControlException(
				"Could not move uploaded file {$this->file['name']}, {$to} is not writable"
			);
			
		$new_path = $to . '/' . $this->file['name'];
		if (file_exists($new_path))
			throw new IOException("File {$new_path} exists!");
		
		move_uploaded_file($this->file['tmp_name'], $new_path);
		
		return new SplFileInfo($new_path);
	}
}