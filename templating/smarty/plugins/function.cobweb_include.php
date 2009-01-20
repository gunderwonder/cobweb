<?php

function smarty_function_cobweb_include($parameters, &$smarty) {
	if (empty($parameters['file']))
		$smarty->trigger_error('cobweb_include: missing \'file\' argument');
	
	$old_template_dir = $smarty->template_dir;
	$old_compire_id = $smarty->compile_id;	
	
	$template = Template::loadTemplate($parameters['file']);
	$smarty->template_dir = dirname($template);
	$smarty->compile_id = dirname($template);

	$result = $smarty->fetch($template);
	
	$smarty->template_dir = $old_template_dir;
	$smarty->compile_id = $old_compire_id;
	
	return $result;

}