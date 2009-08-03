<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP
 * @version    $Revision$
 */
class AllowedMethods extends ActionAnnotation {
	
	public function processRequest(Request $request) {
		if (!in_array($request->method(), $this->value))
			return new HTTPResponseMethodNotAllowed($this->value);
	}
	
	protected function checkConstraints($target) {
		if (!is_array($this->value))
			throw new CobwebException('Allowed methods must be an array of HTTP methods');
	}
	
}