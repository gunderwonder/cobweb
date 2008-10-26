<?php


Cobweb::configure(array(
	'DEBUG' => true,
	'ERROR_REPORTING' => E_ALL | E_STRICT,
	'INSTALLED_MIDDLEWARE' => array(),
	'INSTALLED_APPLICATIONS' => array(),
	
	'URL_PREFIX' => '/cobweb-test',
	
	'TIMEZONE' => 'Europe/Oslo',
	
	'TEMPLATE_PROCESSORS' => array(
		'Cobweb::processTemplate'
	),
	
	'APPEND_SLASH_ON_404' => true
));


if (defined('COBWEB_PROJECT_DIRECTORY'))
	Cobweb::configure(array(
		'APPLICATIONS_PATH' => array(
			COBWEB_DIRECTORY . '/applications',
			COBWEB_PROJECT_DIRECTORY . '/applications'
		),
		'COMPILED_TEMPLATES_DIRECTORY' => COBWEB_PROJECT_DIRECTORY . '/templates/compiled',
		'TEMPLATE_DIRECTORIES' => array(
			COBWEB_PROJECT_DIRECTORY . '/templates',
			COBWEB_DIRECTORY . '/applications/cobweb/templates'
		)
	));
	
else
	Cobweb::configure(array(
		'APPLICATIONS_PATH' => array(
			COBWEB_DIRECTORY . '/applications',
		),
		'TEMPLATE_DIRECTORIES' => array(
			COBWEB_DIRECTORY . '/applications/cobweb/templates'
		)
	));
