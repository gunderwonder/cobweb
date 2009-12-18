<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class RequiresAuthentification extends Annotation { }
class RequiresPermission extends Annotation { }

CobwebLoader::autoload(AUTHENTIFICATION_APPLICATION_DIRECTORY, array(
	'RequiresHTTPAuthentification' => '/annotations/authentification_annotations.inc.php'
));