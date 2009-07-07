<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package    Cobweb
 * @subpackage HTTP
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 */
abstract class Request implements ArrayAccess {
	
	const POST = 'POST';
	const GET  = 'GET';
	const PUT  = 'PUT';
	const DELETE  = 'DELETE';
	const HEAD  = 'HEAD';
	
	protected $properties = array();
	
	abstract public function __construct(
		Dispatcher $dispatcher,
		array $GET,
		array $POST,
		array $COOKIES,
		array $META);
		
	/**
	 * Returns the complete URI of the request
	 * 
	 * @return string the URI of this request
	 */
	abstract public function URI();	
	
	/**
	 * Returns the path component of the URI of this request
	 * 
	 * @return string path of the URI
	 */
	abstract public function path();
	
	/**
	 * Returns the HTTP method of this request
	 * 
	 * @see    Request::POST, Request::GET, Request::DELETE, Request::UPDATE, Request::GET, Request::PUT
	 * @return string method of the request
	 */
	abstract public function method();
	
	/**
	 * Returns the query component of the URI of this request
	 * 
	 * @return string query parh of the URI
	 */
	abstract public function query();
	
	/**
	 * Returns the segment component of the URI of this request
	 * 
	 * @return string query segment of the URI
	 */
	abstract public function hash();
	
	/**
	 * Returns true if the request is made over a secure channel, false otherwise
	 * 
	 * @return bool if the request is secure or not
	 */
	abstract public function isSecure();
	
	/**
	 * Returns true if the request is authenticated
	 * 
	 * @return bool if the request is authenticated
	 */
	abstract public function isAuthenticated();
	
	/**
	 * Returns true if the request did not originate from a referer.
	 * 
	 * @return bool if the request lacks a referer
	 */
	abstract public function isDirect();
	
	/**
	 * Returns true if the request was made using an XMLHTTPRequest
	 * 
	 * @return bool if the request is an AJAX request
	 */
	abstract public function isAJAX();
	
	/**
	 * Returns true if the request has a POST method
	 * 
	 * @return bool if the request is a POST
	 */
	public function isPOST() {
		return $this->method() == self::POST;
	}
	
	/**
	 * Returns true if the request has a GET method
	 * 
	 * @return bool if the request is a GET
	 */
	public function isGET() {
		return $this->method() == self::GET;
	}
	
	/**
	 * Returns true if the request has a PUT method
	 * 
	 * @return bool if the request is a PUT
	 */
	public function isPUT() {
		return $this->method() == self::PUT;
	}
	
	/**
	 * Returns true if the request has a PUT method
	 * 
	 * @return bool if the request is a DELETE
	 */
	public function isDELETE() {
		return $this->method() == self::DELETE;
	}
	
	/**#@+ @ignore */
	public function __set($name, $value) {

		if (isset($this->properties[$name]))
			throw new CobwebException(
				"The property '{$name}' is allready set for this request object");
			
		$this->properties[$name] = $value;
	}
	
	public function __get($name) {
		if (isset($this->properties[$name]))
			return $this->properties[$name];
	}
	
	public function __isset($name) {
		if (isset($this->properties[$name]))
			return true;
	}
	/**#@- */

}