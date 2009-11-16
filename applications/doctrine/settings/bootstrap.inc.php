<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once COBWEB_DIRECTORY . '/vendor/doctrine/Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));

CobwebLoader::autoload(DOCTRINE_APPLICATION_DIRECTORY, array(
	'Model' => '/library/model.class.php',
	'ModelForm' => '/forms/model_form.class.php',
	'CobwebDoctrineManager' => '/library/cobweb_doctrine_manager.class.php'
));

CobwebDoctrineManager::connect(Cobweb::get('DATABASE_SOURCE_NAME'));

if (Cobweb::get('DOCTRINE_MODEL_LOADING', true)) {
	Cobweb::log('Loading models...');
	CobwebDoctrineManager::loadModels(Cobweb::get('DOCTRINE_LAZY_MODEL_LOADING', false));
	
	if (Cobweb::get('DEBUG'))
		Cobweb::info('Done loading models %o', Doctrine::getLoadedModels());
}

	