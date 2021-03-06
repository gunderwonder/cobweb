<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once dirname(__FILE__) . '/smarty_template.class.php';

/**
 * @package Cobweb
 * @subpackage Templating
 */
class SmartyTemplateAdapter extends TemplateAdapter {
	
	private $smarty;
	
	public function __construct() {
		$this->smarty = new SmartyTemplate();
		parent::__construct();
	}
	
	public function interpolate($template, $interpolation_mode = TemplateAdapter::INTERPOLATE_FILE) {
		$this->smarty->assign($this->bindings());
		return $this->smarty->renderFile($template);
	}
	
	
}