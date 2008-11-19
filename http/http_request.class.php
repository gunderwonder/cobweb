<?php
/**
 * @version $Id$ 
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP
 * @version    $Revision$
 */
class HTTPRequest extends Request implements ArrayAccess {
		
	/* @var Dispatcher */
	protected $dispatcher;
	
	/* @var array */
	protected $headers;
	
	/* @var string */
	protected $body;
	
	// /* @var HTTPQueryDictionary */
	// protected $GET;
	// 
	// /* @var HTTPQueryDictionary */
	// protected $POST;
	
	/* @var MutableArray */
	protected $COOKIES;
	
	/* @var ImmutableArray */
	protected $META;
	
	/**
	 * Instantiates a request object with the specified GET, POST, COOKIE and
	 * MEtA parameters.
	 * 
	 * @param Dispatcher $dispatcher event dispatcher
	 * @param array      GET         GET parameters (should contain URL-decoded values)
	 * @param array      POST        POST parameters (should contain URL-decoded values)
	 * @param array      META        an array compatible with the PHP <var>$_SERVER</var> array
	 */
	public function __construct(Dispatcher $dispatcher,
		                        array $GET,
		                        array $POST,
		                        array $META) {

		$this->dispatcher = $dispatcher;
		
		$this->properties['GET']  = new HTTPQueryDictionary($GET);
		$this->properties['POST'] = new HTTPQueryDictionary($POST);
		$this->META = new ImmutableArray($META);
		
		// $this->COOKIES = new MutableArray($_COOKIE);
		
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
	
	/**
	 * Returns true if this request is authenticated (by Cobweb's 
	 * authentification framework or by HTTP authentification), false otherwise
	 * 
	 * @return boolean if this request is authenticated
	 */
	public function isAuthenticated() {
		return !empty($this->META['AUTH_TYPE']) || 
		              (isset($this->user) && $this->user->isAuthenticated());
	}
		
		
	/**
	 * Returns the hash component of the request URI.
	 * 
	 * Note that the hash is never sent to the server (it is used client side),
	 * so this is only useful for testing purposes.
	 * 
	 * @return string hash component of the URI
	 */
	public function hash() {
		$hash_offset = utf8_strpos($this->URI(), '#');
		if ($hash_offset === false)
			return '';
			
		return utf8_substr($this->URI(), $hash_offset + 1);
	}

	/**
	 * Returns the URI of this request (with GET parameters and all).
	 * 
	 * If present, a leading occurence of the `URL_PREFIX' setting is stripped
	 * from the "real" URI.
	 * 
	 * @return string URI of this request
	 */
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
	
	/**
	 * Returns if this request did not originate from a referer or from the 
	 * URI of this request
	 * 
	 * @return boolean if the request is direct
	 */
	public function isDirect() {
		return !isset($this['Referer']) || $this['Referer'] != $this->URI() ;
	}

	/**
	 * Returns if this request was made using an XMLHttpRequest, that is if
	 * the request contains the 'X-Requested-With' header.
	 * 
	 * @return boolean if the request is an AJAX request
	 */
	public function isAJAX() {
		return (isset($this['X-Requested-With']) && 
		        $this['X-Requested-With'] == 'XMLHttpRequest');
	}
	
	/**
	 * @ignore
	 */
	// public function __get($key) {
	// 	Cobweb::log('get %o => %o', $key);
	// 	// if (in_array($key, array('GET', 'POST', 'META', 'COOKIES'))) {
	// 	// 	return $this->$key;
	// 	// }
	// 	return $this->$key;
	// }
	// 
	// public function __set($key, $value) {
	// 	Cobweb::log('%o => %o', $key, $value);
	// 	
	// 	$this->$key = $value;
	// }
	
	/**
	 * Sets a cookie for this request
	 * 
	 * Fires the 'request.cookie_set' event.
	 * 
	 * @see   setcookie()
	 * 
	 * @param  string   $key        cookie name
	 * @param  mixed    $value      cookie value
	 * @param  integer  $expiry     cookie expiry time in seconds since the 1970.
	 *                              Defaults to the time a month from now
	 * @param  string   $path       path for this cookie.
	 *                              Defaults to the path of this request
	 * @param  string   $domain     domain for this cookie.
	 *                              Defaults to the current server name
	 * @param  boolean  $secure     if the cookie is set using HTTPS
	 *                              Defaults to false
	 * @param  boolean  $http_only  if the cookie should only be accessible using
	 *                              HTTP (i.e. inaccessible from JavaScript) 
	 */
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
		 	$path = $this->path();
		if (is_null($domain))
			$domain = $this->META['SERVER_NAME'];

		setcookie($key, $value, $expiry, $path, $domain, $secure, $http_only);
		
		$dispatcher->fire('request.cookie_set', 
			array('cookie' => array($key => $value), 'request' => $this));
	}
	
	/**@+ @ignore */
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
	/**@- */
	
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