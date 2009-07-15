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
	
	public function __construct(Dispatcher $dispatcher, Request $request, $path, array $options) {
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->path = $path;
		$this->options = $options;
	}
	
	public function dispatcher() {
		return $this->dispatcher;
	}
	
	abstract public function get($key, $default = NULL);
	abstract public function set($set, $value, $timeout = NULL);
	abstract public function delete($key);
	abstract public function touch($key, $timeout = NULL);
	
	
}