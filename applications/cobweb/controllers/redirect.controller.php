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
class RedirectController {
	
	public function to($url, $permanent = true) {
		if (!$url) return new HTTPResponseGone();
		
		return $permanent ?
			new HTTPResponsePermanentRedirect($url) :
			new HTTPResonseRedirect($url);
	}
	
	public function toAction($label, array $arguments = array()) {
		return parent::redirect('@' . $label, $arguments);
	}
	
}