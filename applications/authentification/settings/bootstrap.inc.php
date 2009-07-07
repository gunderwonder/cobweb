<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class RequiresAuthentification extends Annotation { }
class RequiresPermission extends Annotation { }

CobwebLoader::register('Authentificator', dirname(__FILE__) . '/../authentificator.interface.php');
CobwebLoader::register('CobwebAuthentificator', dirname(__FILE__) . '/../backends/cobweb_authentificator.class.php');