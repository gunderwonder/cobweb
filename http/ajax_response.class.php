<?php
/**
 * @version $Id$
 */

/**
 * Represents an HTTP response to an AJAX request
 * 
 * The {@link AJAXResponse} class implements the server end side of a very 
 * simple protocol using JSON to make communication between client side
 * Javascript and Cobweb easier. This protocol defines a set of commands for
 * the client side to exectute (e.g. redirect to a URL or display an 
 * informational message to the user), a messaging mechanism, and the means
 * to convey the status of a server side operation (i.e 
 * {@link AJAXResponse::SUCCESS} or {@link AJAXResponse::FAILURE}).
 * 
 * @package    Cobweb
 * @subpackage HTTP
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 */
class AJAXResponse extends HTTPResponse {
	
	// AJAX RESPONSE COMMANDS
	/**
	 * Command for specifying a message to be displayed
	 * 
	 * Specify the message to be displayed with the <var>element</var> key 
	 * of the <var>$extra</var> array.
	 * @var string
	 */
	const MESSAGE  = 'message';
	
	/**
	 * Command for updating the contents of an element
	 * 
	 * Specify the ID of the element to update to with the <var>element</var> key 
	 * of the <var>extra</var> array.
	 * @var string
	 */
	const UPDATE   = 'update';
	
	/**
	 * Command for replacing an element
	 * 
	 * Specify the ID of the element to replace to with the <var>element</var> key 
	 * of the <var>extra</var> array.
	 * @var string
	 */
	const REPLACE  = 'replace';
	
	/**
	 * Command for a client side redirect to a URL
	 * 
	 * Specify the URL to redirect to with the <var>url</var> key 
	 * of the <var>extra</var> array.
	 * @var string
	 */
	const REDIRECT = 'redirect';
	const CALL     = 'call';
	
	
	// AJAX RESPONSE STATUSES
	/**
	 * Indicates a successful status
	 * @var int
	 */
	const SUCCESS = 1;
	
	/**
	 * Indicates a unsuccessful status
	 * @var int
	 */
	const FAILURE = 0;
	
	
	/**
	 * Instantate an {@link AJAXResponse}
	 * 
	 * @see    HTTPResponse, JSON
	 * 
	 * @param  string  $status   the status of the response
	 * @param  string  $command  the command of the response
	 * @param  string  $messge   the message of the response
	 * @param  array   $extra    key-value pairs of extra data to transmit
	 * @param  string  $code     the HTTP status code of this response
	 */
	public function __construct($status = self::SUCCESS,
		                        $command = self::MESSAGE,
		                        $message = '',
		                        array $extra = NULL,
		                        $code = HTTPResponse::OK) {

		$body = array(
			'status' => $status,
			'command' => $command,
			'message' => $message
		);
		
		// merge extra values if present and JSON encode the body
		if (!$extra)
			$extra = array();
		$body = JSON::encode(array_merge($body, $extra));
			
		parent::__construct($body, $code, MIMEType::JSON);
	}
	
	/**
	 * Convenience function to create an {@link AJAXResponse} with a 
	 * successful status with an optional message
	 * 
	 * @param  string  $message  the message of the response
	 * @param  array   $extra    key-value pairs of extra data to transmit
	 * 
	 * @return AJAXResponse      the response object
	 */
	public static function success($message = '', array $extra = NULL) {
		return new AJAXResponse(
			AJAXResponse::SUCCESS, 
			AJAXResponse::MESSAGE,
			$message,
			$extra
		);
	}
	
	/**
	 * Convenience method to create an {@link AJAXResponse} with an
	 * unsuccessful status and an optional message
	 * 
	 * @see    AJAXResponse::__construct()
	 * 
	 * @param  string  $message  the message of the response
	 * @param  array   $extra    key-value pairs of extra data to transmit
	 * 
	 * @return AJAXResponse      the response object
	 */
	public static function failure($message = '', array $extra = NULL) {
		return new AJAXResponse(
			AJAXResponse::FAILURE, 
			AJAXResponse::MESSAGE,
			$message,
			$extra
		);
	}
	
	/**
	 * Convenience method to create an {@link AJAXResponse} with a 
	 * command to redirect to the specified <var>$url</var>.
	 * 
	 * @param  string  $url      the URL to redirect to
	 * @param  string  $message  the message of the response
	 * 
	 * @return AJAXResponse      the response object
	 */
	public static function redirect($url, $message = '') {
		return new AJAXResponse(
			AJAXResponse::SUCCESS, 
			AJAXResponse::REDIRECT,
			$message,
			array('url' => $url)
		);
	}
	
	public static function send($content) {
		return new AJAXResponse(
			AJAXResponse::SUCCESS,
			AJAXResponse::UPDATE,
			'',
			array('content' => $content)
		);
	}
}

?>