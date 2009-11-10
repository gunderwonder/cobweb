<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP
 * @version    $Revision$
 */
abstract class Response implements ArrayAccess {
	
	/** @var mixed */
	public $body;
	
	/**
	 * Append the specified content to the body of this response.
	 * @param  string   $contents content to write
	 * @return Response           this response
	 */
	abstract public function write($contents);
	
	/**
	 * Finalizes and outputs this response.
	 * @return Response this response object
	 */
	abstract public function flush();
	
	/**
	 * Returns the HTTP status code of this response
	 * 
	 * @return integer HTTP status code
	 */
	abstract public function code();
}