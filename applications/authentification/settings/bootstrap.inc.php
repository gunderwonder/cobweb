<?php

class RequiresAuthentification extends Annotation { }

CobwebLoader::register('Authentificator', dirname(__FILE__) . '/../authentificator.interface.php');
CobwebLoader::register('CobwebAuthentificator', dirname(__FILE__) . '/../backends/cobweb_authentificator.class.php');