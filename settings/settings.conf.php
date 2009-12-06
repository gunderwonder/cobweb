<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

$settings = array(
	'DEBUG' => true,
	'ERROR_REPORTING' => E_ALL | E_STRICT,
	'INSTALLED_MIDDLEWARE' => array(),
	'INSTALLED_APPLICATIONS' => array('cobweb'),
	'URL_PREFIX' => '',
	'TIMEZONE' => 'Europe/Oslo',
	'TEMPLATE_PROCESSORS' => array('Cobweb::processTemplate'),
	'APPEND_SLASH_ON_404' => true,
	'URL_CONFIGURATION' => array('^$' => 'cobweb.cobweb.start'),
	'LOGIN_URL' => '/accounts/login',
	'LOGIN_REDIRECT_URL' => '/accounts/profile'
);

if (defined('COBWEB_PROJECT_DIRECTORY'))
	$settings = array_merge($settings, array(
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
	$settings = array_merge($settings, array(
			'APPLICATIONS_PATH' => array(COBWEB_DIRECTORY . '/applications'),
			'TEMPLATE_DIRECTORIES' => array(COBWEB_DIRECTORY . '/applications/cobweb/templates')
	));
return $settings;