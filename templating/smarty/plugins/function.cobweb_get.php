<?php

/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


function  smarty_function_cobweb_get($parameters, &$smarty) {
	return Cobweb::get($parameters['key']);
}