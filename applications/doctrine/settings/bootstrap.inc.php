<?php

require_once COBWEB_DIRECTORY . '/vendor/doctrine/lib/Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));

CobwebLoader::register('Model', realpath(dirname(__FILE__) . '/../library/model.class.php'));
CobwebLoader::register('CobwebDoctrineManager', dirname(__FILE__) . '/../library/cobweb_doctrine_manager.class.php');


CobwebDoctrineManager::connect(Cobweb::get('DATABASE_SOURCE_NAME'));

Cobweb::log('Loading models...');
CobwebDoctrineManager::loadModels();

if (Cobweb::get('DEBUG'))
	Cobweb::info('Done loading models %o', Doctrine::getLoadedModels());