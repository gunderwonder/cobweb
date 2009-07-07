<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

error_reporting(E_ALL);

require_once COBWEB_DIRECTORY . '/core/exceptions.inc.php';
require_once COBWEB_DIRECTORY . '/core/cobweb_loader.class.php';
require_once COBWEB_DIRECTORY . '/core/cobweb_declaration.interface.php';

require_once COBWEB_DIRECTORY . '/core/cobweb.class.php';

spl_autoload_register(array('Cobweb', 'load'));
set_error_handler(array('Cobweb', 'handleError'));

require_once COBWEB_DIRECTORY . '/utilities/string.inc.php';
require_once COBWEB_DIRECTORY . '/utilities/string_inflection.inc.php';
require_once COBWEB_DIRECTORY . '/utilities/array.inc.php';

require_once COBWEB_DIRECTORY . '/vendor/utf8/utf8.php';
require_once COBWEB_DIRECTORY . '/vendor/utf8/str_ireplace.php';