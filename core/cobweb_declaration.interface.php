<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Core
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
interface CobwebDeclaration {
	public function configuration();
	public function request();
	public function dispatcher();
	public function resolver();
}

?>