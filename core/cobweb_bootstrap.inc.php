<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

error_reporting(E_ALL);

require COBWEB_DIRECTORY . '/core/exceptions.inc.php';
require COBWEB_DIRECTORY . '/core/cobweb_loader.class.php';
require COBWEB_DIRECTORY . '/core/cobweb_declaration.interface.php';
       
require COBWEB_DIRECTORY . '/core/cobweb.class.php';

spl_autoload_register(array('CobwebLoader', 'load'));
set_error_handler(array('Cobweb', 'handleError'));

require COBWEB_DIRECTORY . '/vendor/utf8/utf8.php';
require COBWEB_DIRECTORY . '/vendor/utf8/str_ireplace.php';
       
require COBWEB_DIRECTORY . '/utilities/miscellaneous.inc.php';
require COBWEB_DIRECTORY . '/utilities/string.inc.php';
require COBWEB_DIRECTORY . '/utilities/string_inflection.inc.php';
       
require COBWEB_DIRECTORY . '/utilities/array.inc.php';