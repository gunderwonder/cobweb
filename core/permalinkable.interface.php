<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


/**
 * Interface to provide a canonical path or URI to a resource representing an object
 * 
 * @package Cobweb
 * @subpackage Core
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @return string canonical link address to the object 
 */
interface Permalinkable {
	public function permalink();
}