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
 * @needsdocumentation
 */
interface CobwebDeclaration {
	public function configuration();
	public function createConfiguration(array $configuration);
	
	public function request();
	public function createRequest(array $configuration);
	
	public function dispatcher();
	public function createDispatcher(array $configuration);
	
	public function resolver();
	public function createResolver(array $configuration);
	
	public function applicationManager();
	public function createApplicationManager(array $configuration);
	
	public function middlewareManager();
	public function createMiddlewareManager(array $configuration);
}

?>