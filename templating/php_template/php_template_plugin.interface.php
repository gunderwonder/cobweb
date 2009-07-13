<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Templating
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 */
interface PHPTemplatePlugin {
	
	/**
	 * Plugin implementations must implement this method, which should return
	 * an array of mappings between method names used in inline template code
	 * and the actual method to invoke on the plugin class.
	 * 
	 * @return array the plugins of this plugin implementation
	 */
	public function __plugins(TemplateAdapter $adapter);
	
}