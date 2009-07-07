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
	
	public $body;
	
	/**
	 * Write the specified content to this response
	 * @param  string   $contents content to write
	 * @return Response           this response
	 */
	abstract public function write($contents);
	
	/**
	 * Finalizes this response
	 * @return Response
	 */
	abstract public function flush();
	
	/**
	 * Returns the HTTP status code of this response
	 * @return integer HTTP status code
	 */
	abstract public function code();
}