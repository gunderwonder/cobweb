<?php

function smarty_function_cobweb_include($parameters, &$smarty) {
	if (empty($parameters['file']))
		$smarty->trigger_error('cobweb_include: missing \'file\' argument');
		
	$t = new SmartyTemplate();
	$t->assign($smarty->get_template_vars());

	return $t->fetch(Template::loadTemplate($parameters['file']));

}