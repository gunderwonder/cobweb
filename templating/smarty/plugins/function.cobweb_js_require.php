<?php

$GLOBALS['COBWEB_JS_DEPENDENCIES'] = array(
	'prototype'     => array('prototype'),
	'scriptaculous' => array('prototype', 'scriptaculous'),
	'cobweb'        => array('scriptaculous', 'cobweb'),
	'firebug'       => array('firebug')
);

function smarty_function_cobweb_js_require($parameters, &$smarty) {
	
	global $COBWEB_JS_DEPENDENCIES;
	$js_paths = array (
		'prototype' => Cobweb::setting('SITE_URL_PREFIX') . '/cobweb/vendor/prototype.js',
		'scriptaculous' => Cobweb::setting('SITE_URL_PREFIX') . '/cobweb/vendor/scriptaculous/scriptaculous.js',
		'cobweb' => Cobweb::setting('SITE_URL_PREFIX') . '/cobweb/js/cobweb.js',
		'firebug' => Cobweb::setting('SITE_URL_PREFIX') . '/cobweb/vendor/firebug/firebug.js'
	);

	if (empty($parameters['libraries']))
		$smarty->trigger_error('js_require: missing \'libraries\' argument');

	$libraries = preg_split('/\s*,\s*/', $parameters['libraries']);
	
	$requires = array();
	$others = array();
	
	foreach ($libraries as $library)
		if (array_key_exists($library, $COBWEB_JS_DEPENDENCIES))
			$requires = array_merge($COBWEB_JS_DEPENDENCIES[$library], $requires);
	
	$requires = array_unique($requires);
	usort($requires, 'smarty_function_cobweb_js_require_sort_dependecies');
	
	$html = '';
	foreach ($requires as $library)
		$html .= "<script src=\"{$js_paths[$library]}\" type=\"text/javascript\" charset=\"utf-8\"></script>\n\t";
		
	return $html;
	
}

function smarty_function_cobweb_js_require_sort_dependecies($a, $b) {
	global $COBWEB_JS_DEPENDENCIES;
	
	$i_a = array_index_of_key($a, $COBWEB_JS_DEPENDENCIES);
	$i_b = array_index_of_key($b, $COBWEB_JS_DEPENDENCIES);
	
	if ($i_a < $i_b)
		return -1;
	else if ($i_a == $i_b)
		return 0;
	else
		return 1;
	
}