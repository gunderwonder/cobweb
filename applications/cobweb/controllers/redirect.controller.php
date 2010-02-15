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
class RedirectController extends Controller {
	
	public function to($url, $permanent = true) {
		return $this->redirect($url, $permanent);
	}
	
	public function toAction($label, array $arguments = array()) {
		return parent::redirect('@' . $label, $arguments);
	}
	
}