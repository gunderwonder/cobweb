<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Cache
 * @version    $Revision$
 */
abstract class CacheEngine {
	
	/** @var Dispatcher */ 
	protected $dispatcher;
	
	/** @var Request */
	protected $request;
	
	/** @var array */
	protected $options;
	
	protected $hostname = NULL;
	protected $path = NULL;
	protected $port = NULL;
	protected $username = NULL;
	protected $password = NULL;
	protected $fragment = NULL;
	
	public function __construct(Dispatcher $dispatcher, Request $request, ImmutableArray $uri) {
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		
		$this->hostname = $uri->get('host', NULL);
		$this->path = $uri->get('path', NULL);
		$this->port = $uri->get('port', NULL);
		$this->username = $uri->get('user', NULL);
		$this->password = $uri->get('pass', NULL);
		$this->fragment = $uri->get('fragment', NULL);
		
		$this->options = array();
		parse_str($uri->get('query', ''), $this->options);
		
		$this->initialize();
	}
	
	protected function initialize() { }
	
	public function dispatcher() {
		return $this->dispatcher;
	}
	
	abstract public function get($key, $default = NULL);
	abstract public function set($set, $value, $timeout = NULL);
	abstract public function delete($key);
	abstract public function touch($key, $timeout = NULL);
	
	
}