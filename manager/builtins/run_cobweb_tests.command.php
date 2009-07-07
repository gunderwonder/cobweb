<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Management
 */
class RunCobwebTestsCommand extends CobwebManagerCommand {
	
	public function execute() {
		chdir(COBWEB_DIRECTORY . '/test');
		system('run-tests');
	}
	
}