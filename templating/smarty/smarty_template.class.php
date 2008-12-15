<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/mit-license.php MIT
 */

define('SMARTY_DIR', COBWEB_DIRECTORY . '/vendor/smarty/');
require_once(SMARTY_DIR . 'Smarty.class.php');

/**
 * Simple subclass of Smarty with Cobweb integration
 * 
 * @author  Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class SmartyTemplate extends Smarty {
	
	/**
	 * @return SmartyTemplate
	 */
	public function __construct() {
		$this->error_reporting = E_ALL; 
		$this->debugging = false;
		$this->plugins_dir = array_merge(
			$this->plugins_dir,
			array(dirname(__FILE__) . '/plugins'),
			Cobweb::get('SMARTY_PLUGIN_DIRECTORIES', array())
		);
	}
	
	/**
	 * @param  string $template
	 * @return string
	 */
	public function renderFile($template) {
		
		// set $template_dir and $compile_id with respect to template name
		$this->template_dir = dirname($template);
		$this->compile_id = dirname($template); 
		$this->compile_dir = Cobweb::get('COMPILED_TEMPLATES_DIRECTORY');

		$result = parent::fetch(basename($template));
		
		return $result;
	}
}