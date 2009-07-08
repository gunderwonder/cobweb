<?php

define('COBWEB_DIRECTORY', '%cobweb_directory%');
define('COBWEB_PROJECT_DIRECTORY', "%project_directory%");
define('COBWEB_WWW_ROOT', COBWEB_PROJECT_DIRECTORY . '/www');

require_once COBWEB_DIRECTORY . '/core/cobweb_bootstrap.inc.php';

Cobweb::start();