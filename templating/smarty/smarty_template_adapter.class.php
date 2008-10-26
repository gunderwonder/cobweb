<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/mit-license.php MIT 
 */

require_once dirname(__FILE__) . '/smarty_template.class.php';

class SmartyTemplateAdapter extends TemplateAdapter {
	
	private $smarty;
	
	public function __construct() {
		$this->smarty = new SmartyTemplate();
		parent::__construct();
	}
	
	public function renderFile($file) {
		$this->smarty->assign($this->bindings());
		return $this->smarty->renderFile($file);
	}
	
	
}