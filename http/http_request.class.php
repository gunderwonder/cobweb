<?php
/* $Id$ */

/**
 * Encapsulates an HTTP request
 * 
 * The request object is used throughout Cobweb to represent the current client
 * request. It provides access to GET and POST parameters as well as HTTP header
 * information. In addition, the Cobweb extensions such as middleware classes
 * may add its own properties to the request object
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP
 * @version    $Revision$
 * 
 */
class HTTPRequest extends Request implements ArrayAccess {
		
	protected
		$dispatcher,
		$headers,
		$body;
	
	protected
		$_GET,
		$POST,
		$COOKIES,
		$META;
	
	public function __construct(Dispatcher $dispatcher,
		                        array $GET,
		                        array $POST,
		                        array $META,
		                        array $COOKIES = NULL) {
			
		$this->dispatcher = $dispatcher;
		
		$this->GET  = new HTTPQueryDictionary($GET);
		$this->POST = new HTTPQueryDictionary($POST);
		$this->META = new ImmutableArray($META);
		
		$this->COOKIES = new MutableArray($_COOKIE);
		
		$other_headers = array('CONTENT_TYPE', 'CONTENT_LENGTH');
		$this->headers = array();
		
		foreach($this->META as $header => $value) {
			if (strpos($header, 'HTTP_') === 0 || in_array($header, $other_headers)) {
				
				$name = str_replace(array('HTTP_', '_'), array('', '-'), $header);
				$this->headers[$name] = $value;
			}
		}		
	}

	/**
	 * Returns the HTTP method of this request 
	 * (one of `GET', 'POST', 'PUT', 'DELETE' or 'HEAD')
	 * 
	 * @return string method of the request
	 */
	public function method() {
		return $this->META['REQUEST_METHOD'];
	}
	
	/**
	 * Returns the body, headers and all of this request
	 * 
	 * @return string body of the request
	 */
	public function body() {
		if (!$this->body)
			$this->body = @file_get_contents('php://input');
			
		return $this->body;
	}
	
	/**
	 * Returns the domain (the server name) of this request URI
	 * 
	 * @return string server name
	 */
	public function domain() {
		$this->META['SERVER_NAME'];
	}
	
	/**
	 * Returns true if the request was made over a secure channed 
	 * (i.e using HTTPS), false otherwise.
	 * 
	 * @return boolean whether the request was made using HTTPs or not
	 */
	public function isSecure() {
		return !empty($this->META['HTTPS']);
	}
	
	public function isAuthenticated() {
		return !empty($this->META['AUTH_TYPE']) || 
		              (isset($this->user) && $this->user->isAuthenticated());
	}
		
	public function hash() {
		
		$hash_offset = utf8_strpos($this->URI(), '#');
		if ($hash_offset === false)
			return '';
			
		return utf8_substr($this->URI(), $hash_offset + 1);
	}
	
	public function URI() {
		return lstrip($this->META['REQUEST_URI'], Cobweb::get('URL_PREFIX'));
	}

		
	public function path() {
		$uri = $this->URI();
		$query_part = utf8_strpos($uri, '?');
		if ($query_part === false)
			return $uri;

		return utf8_substr($uri, 0, $query_part);
	}
	
	public function query() {
		 if (isset($this->META['QUERY_STRING']))
			$this->META['QUERY_STRING'];
		
		$hash = $this->hash();
		$hash = $hash ? '#' . $hash : '';

		return lstrip(rstrip(lstrip($this->URI(), $this->path()), $hash), '?');
	}
	
	public function bits() {
		return explode('/', trim($this->path(), '/'));
	}
	
	public function isDirect() {
		return !isset($this['Referer']) || $this['Referer'] != $this->URI() ;
	}

	public function isAJAX() {
		return (isset($this['X-Requested-With']) && 
		        $this['X-Requested-With'] == 'XMLHttpRequest');
	}
	
	public function __get($key) {
		if (in_array($key, array('GET', 'POST', 'META', 'COOKIES'))) {
			$key = '_' . $key;
			return $this->$key;
		}
	}
	
	public function setCookie($key, 
		                      $value,
		                      $expiry = NULL,
		                      $path = NULL,
		                      $domain = NULL,
		                      $secure = false,
		                      $http_only = false) {
			
		if (is_null($expiry))
			$expiry = time() + 60 * 60 * 24 * 30;
		if (is_null($path))
		 	$path = $this->path;
		if (is_null($domain))
			$domain = $this->META['SERVER_NAME'];

		setcookie($key, $value, $expiry, $path, $domain, $secure, $http_only);
		
		$dispatcher->fire('request.cookie_set', 
			array('cookie' => array($key => $value), 'request' => $this));
	}
	

	public function offsetExists($header_name) {
		$header_name = strtoupper($header_name);
		return array_key_exists($header_name, $this->headers);
	}
	
	public function offsetGet($header_name) {
		$header_name = strtoupper($header_name);
		return $this->headers[$header_name];
	}
	
	public function offsetSet($key, $value) { 
		throw new KeyError("HTTP request headers are immutable");
	}
	
	public function offsetUnset($key) { 
		throw new KeyError("HTTP request headers are immutable");
	}
	
	public function __toArray() {
		$formatted_headers = array();
		foreach ($this->headers as $header => $value) {
			$formatted_key = preg_replace_callback(
				'/-(\w)/',
				create_function('$m', 'return \'-\' . ucfirst($m[0][1]);'),
				ucfirst(strtolower($header))
			);
			$formatted_headers[$formatted_key] = $value;
		}
		
		return $formatted_headers;
	}

}