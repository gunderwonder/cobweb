<?php
/**
 * @package Cobweb
 * @package Miscellaneous
 * @version $Id$
 * @author  Øystein Riiser Gundersen <oystein@upstruct.com>
 */

/**
 * Interface to provide a canonical path or URI to a resource representing an object
 * 
 * @return string canonical link address to the object 
 */
interface Permalinkable {
	public function permalink();
}