
<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

function __shell_print_var($var) {
	echo stringify($var);
}

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Management
 */
class ShellCommand extends CobwebManagerCommand {
	
	public function configure() {
		// $this->requiresProject();
	}
	
	public function execute() {
		foreach (Cobweb::get('INSTALLED_APPLICATIONS') as $application)
			Cobweb::loadApplication($application);
		
		require_once COBWEB_DIRECTORY . '/vendor/php-shell/PHP_Shell-0.3.0/scripts/php-shell-cmd.php';
	}
}