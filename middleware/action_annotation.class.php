<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Middleware
 * @version    $Revision$
 */
abstract class ActionAnnotation extends Annotation implements RequestProcessor {
	
	/** @var Dispatcher */
	protected $dispatcher;
	
	public function prepare(Dispatcher $dispatcher) {
		$this->dispatcher = $dispatcher;
		$this->initialize();
		return $this;
	}
	
	protected function initialize() { }
	
	public function processRequest(Request $request) {
		return NULL;
	}
	
	public function processResponse(Request $request, Response $response) {
		return $response;
	}
	
	public function processAction(Request $request, Action $action) {
		return NULL;
	}
	
	public function processException(Request $request, Exception $exception) {
		return NULL;
	}
	
}