<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class RequiresAuthentification extends Annotation { }
class RequiresPermission extends Annotation { }

CobwebLoader::autoload(AUTHENTIFICATION_APPLICATION_DIRECTORY, array(
	'Authentificator', '/libarary/authentificator.interface.php',
 	'ModelAuthentificator', '/library/model_authentificator.class.php'
));
