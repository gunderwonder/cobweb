<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Cobweb application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class SessionNotification {
	
	const ERROR = 'error';
	const INFO = 'info';
	const WARNING = 'warning';
	const SUCCESS = 'success';
	
	protected $type = NULL;
	protected $message = NULL;
	protected $options = NULL;
	
	public function __construct($message, $type, $options = array()) {
		$this->message = $message;
		$this->type = $type;
		$this->options = $options;
	}
	
	public function message() {
		return $this->message;
	}
	
	public function type() {
		return $this->type;
	}
	
	public function toArray() {
		return array(
			'message' => $this->message,
			'type' => $this->type,
			'options' => $this->options
		);
	}
}

/**
 * @package Cobweb
 * @subpackage Cobweb application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class SessionNotificationManager implements IteratorAggregate {
	
	protected $notifications = array();
	protected $session = NULL;
	
	const SESSION_KEY = '__notifications__';
	
	public function __construct(Session $session) {
		$this->session = $session;
	}
	
	protected function notify($message, $type, $options = array()) {
		$this->notifications[] = new SessionNotification($message, $type);
		return $this;
	}
	
	public function info($message, $options = array()) {
		$this->notify($message, SessionNotification::INFO, $options);
		return $this;
	}
	
	public function error($message, $options = array()) {
		$this->notify($message, SessionNotification::ERROR, $options);
		return $this;
	}
	
	public function warning($message, $options = array()) {
		$this->notify($message, SessionNotification::WARNING, $options);
		return $this;
	}
	
	public function success($message, $options = array()) {
		$this->notify($message, SessionNotification::SUCCESS, $options);
		return $this;
	}
	
	public function clear() {
		$old_notifications = $this->notifications;
		$this->notifications = array();
		return $old_notifications;
	}
	
	public function get() {
		return $this->notifications;
	}
	
	public function getIterator() {
		return new ArrayIterator(array_reverse($this->notifications));
	}
}

/**
 * @package Cobweb
 * @subpackage Cobweb application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class SessionNotificationMiddleware extends Middleware {
	
	public function processRequest(Request $request) {
		if (isset($request->notifications))
			return NULL;
		
		if (!isset($request->session))
			throw new CobwebConfigurationException(
				'Notification middleware requires the session application to be installed'
			);
		
		if (!isset($request->session[SessionNotificationManager::SESSION_KEY]))
			$request->session[SessionNotificationManager::SESSION_KEY] = 
				new SessionNotificationManager($request->session);
			
		$request->notifications = $request->session[SessionNotificationManager::SESSION_KEY];
	}
	
	public function processResponse(Request $request, Response $response) {
		if ($response instanceof AJAXResponse) {
			$notifications = array();
			foreach ($request->notifications as $notification)
				$response->body['notifications'][] = $notification->toArray();
		}
		
		if (Cobweb::get('AUTOCLEAR_SESSION_NOTIFICATIONS', true) &&
			!$this->isRedirectResponse($request))
			$request->notifications->clear();
		return $response;
	}
	
	private function isRedirectResponse($response) {
		return  $response instanceof HTTPResponseRedirect ||
		       ($response instanceof AJAXResponse && 
			    $response->body['command'] == 'redirect');
	}
	
}