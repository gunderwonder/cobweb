<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package     Cobweb
 * @subpackage  HTTP
 * @author      Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version     $Revision$ 
 */
class HTTPResponse extends Response {
	
	protected $headers = NULL;
	protected $response_code = 200;
	
	const OK                    = '200 OK';
	
	const REDIRECT              = '302 Found';
	const PERMANENT_REDIRECT    = '301 Moved Permanently';
	const NOT_MODIFIED          = '304 Not Modified';
	
	const BAD_REQUEST           = '400 Bad Request';
	const UNAUTHORIZED          = '401 Unauthorized';
	const FORBIDDEN             = '403 Forbidden';
	const NOT_FOUND             = '404 Not Found';
	const METHOD_NOT_ALLOWED    = '405 Method Not Allowed';
	const GONE                  = '410 Gone';
	
	const INTERNAL_SERVER_ERROR = '500 Internal Server Error';

	
	private static $HTTP_codes = array(
		200 => self::OK,
		400 => self::BAD_REQUEST,
		401 => self::UNAUTHORIZED,
		403 => self::FORBIDDEN,
		404 => self::NOT_FOUND,
		405 => self::METHOD_NOT_ALLOWED,
		410 => self::GONE,
		302 => self::REDIRECT,
		301 => self::PERMANENT_REDIRECT,
		500 => self::INTERNAL_SERVER_ERROR
	);
	
	public function __construct($body = '', 
		                        $response_code = self::OK,
		                        $mime_type = MIMEType::HTML) {
		$this->body = '';
		$this->write(is_object($body) ? $body->__toString() : $body);
			
		if (is_int($response_code))
			$this->response_code = self::$HTTP_codes[$response_code];
		else
			$this->response_code = $response_code;
		$this->mime_type = $mime_type;
		
		
		$this->headers = new MutableArray();
		foreach(headers_list() as $h) {
			list($header_name, $value) = preg_split('/:\s/', $h);
			$this[$header_name] = $value;
		}
		
		$this['Content-Type'] = $mime_type;
		
	}
	
	public function code() {
		return $this->response_code;
	}
	
	protected function sendHeaders() {
		if (headers_sent())
			throw new HeadersSentException("Headers already sent!");
		
		header("HTTP/1.1 {$this->response_code}");
		foreach ($this->headers as $header_name => $header_value)
			header("$header_name: $header_value");
	}
	
	
	public function write($contents) {
		$this->body .= $contents;
	}
	
	public function flush() {
		$this->sendHeaders();
		print $this->body;
		flush();
	}
	
	/**@+ @ignore */
	public function offsetExists($key) {
		if (is_int($key))
			return false;
		
		return array_key_exists($key, $this->headers);
	}
	
	public function offsetGet($key) {
		return $this->headers[$key];
	}
	
	public function offsetSet($key, $value) {
		if (is_int($key))
			throw new KeyException('HTTPResponse::offsetSet() does not allow numeric indices');

		$this->headers[$key] = $value;
		
	}
		
	public function offsetUnset($key) {
		if (is_int($key))
			throw new KeyException('HTTPResponse::offsetUnset() does not allow numeric indices');
		
		$this->headers[$key] = '';
	}
	/**@- */
	
}

class HTTPResponseRedirect extends HTTPResponse {
	public function __construct($location) {
		parent::__construct("", HTTPResponse::REDIRECT);
		$this['Location'] = $location;	
	}
}

class HTTPResponsePermanentRedirect extends HTTPResponse {
	public function __construct($location) {
		parent::__construct("", HTTPResponse::PERMANENT_REDIRECT);
		$this['Location'] = $location;	
	}
}

class HTTPResponseGone extends HTTPResponse {
	
	public function __construct($location) {
		parent::__construct("", HTTPResponse::GONE);
	}
}

class HTTPResponseUnauthorized extends HTTPResponse {
	public function __construct($body = "") {
		parent::__construct($body, self::UNAUTHORIZED);
	}
}

class HttpResponseBadRequest extends HTTPResponse {
	public function __construct($body = "400 Bad Request") {
		parent::__construct($body, self::BAD_REQUEST);
	}
}

class HTTPResponseNotFound extends HTTPResponse {
	public function __construct($body = "404 Not Found") {
		parent::__construct($body, self::NOT_FOUND);
	}
}

class HTTPResponseForbidden extends HTTPResponse {
	public function __construct($body = "404 Forbidden") {
		parent::__construct($body, self::FORBIDDEN);
	}
}

class HTTPResponseNotModified extends HTTPResponse {
	public function __construct($expiration_seconds = 3600) {
		parent::__construct('', self::NOT_MODIFIED);
		$this['Date'] = gmdate('r');
		$this['Cache-Control'] = 'must-revalidate';
		$this['Expires'] = CWDateTime::create('now + 3600 seconds')->format(DateTime::RFC1123);
	}
}

class HTTPResponseMethodNotAllowed extends HTTPResponse {
	public function __construct(array $allowed_methods) {
		parent::__construct('405 Method Not Allowed', self::METHOD_NOT_ALLOWED);
		$this['Allow'] = implode(', ', $allowed_methods);
	}
}

?>