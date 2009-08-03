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
class VaryOnHeaders extends ActionAnnotation {
	
	public function processResponse(Request $request, Response $response) {
		// TODO: check if the request is cachable
		// XXX: we probably shouldn't be so naïve here 
		$response['Vary'] = implode(',', $this->value);
		return $response;
	}
	
	protected function checkConstraints($target) {
		if (!is_array($this->value))
			throw new CobwebException('Vary headers settings must be an array of header names');

	}
	
}