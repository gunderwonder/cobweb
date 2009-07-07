<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class CobwebMiddleware extends Middleware {
	private $error = false;
	
	public function processException(Request $request, Exception $e) {
		
		if ($this->error)
			return NULL;
		$this->error = true;

		if (Cobweb::get('DEBUG'))
			return Controller::invoke('cobweb.debug.debugger', array('exception' => $e));
		else if ($e instanceof HTTP404)
			return Controller::invoke(Cobweb::get('404_ACTION', 'cobweb.cobweb.not_found_404'));
		else
			return Controller::invoke('cobweb.cobweb.graceful_exception', array('exception' => $e));
			
	}
}