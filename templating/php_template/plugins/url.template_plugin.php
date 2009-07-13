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
class URLTemplatePlugin implements PHPTemplatePlugin {
	
	public function url($label, array $arguments = array()) {
		try {
			return Cobweb::get('__RESOLVER__')->reverse($label, $arguments);
		} catch (Exception $e) {
			if (Cobweb::get('DEBUG'))
				throw $e;
			return '#';
		}
	}

	public function __plugins(TemplateAdapter $adapter) {
		return array('url' => 'url');
	}
}