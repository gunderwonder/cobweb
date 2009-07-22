<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once COBWEB_DIRECTORY . '/vendor/firephp/FirePHP.class.php';

/**
 * @package    Cobweb
 * @subpackage Logging
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 * @since      r223
 * @internal
 */
class CobwebFirePHP extends FirePHP {
	protected $cw_request;
	
	public function __construct(Response $response) {
		$this->cw_response = $response;
		parent::__construct();
		$this->setObjectFilter('User', array('password'));
	}
	
	/**
	 * Overrides FirePHP routine for setting headers. Buffers the headers in
	 * the Cobweb response object.
	 */
	protected function setHeader($name, $value) {
		$this->cw_response[$name] = $value;
		return true;
	}
}

/**
 * @package    Cobweb
 * @subpackage Logging
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 * @since      r223 
 */
class FirePHPLogFormatter extends LogFormatter {
	
	protected static $instance = NULL;
	
	protected static function instance(Response $response) {
		if (is_null(self::$instance))
			self::$instance = new CobwebFirePHP($response);
		return self::$instance;
	}
	
	public function format(Response $response) {
		if ($this->logger->isEmpty())
			return '';
			
		$firephp = self::instance($response);
		$firephp->group($this->logger->name(), array('Collapsed' => true));
		foreach ($this->logger as $invocation) {

			$method = $invocation[0];
			$things = $invocation[1];
			
			// use first argument as label if it is a string
			$label = '';
			if (count($things) > 1)
				if (is_string($things[0]))
					$label = array_shift($things);
			if (count($things) == 1)
				$things = $things[0];
				
			if (method_exists($firephp, $method))
				$firephp->$method($things, $label);
		}
		$firephp->groupEnd();
	}
}