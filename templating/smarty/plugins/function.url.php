<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


function smarty_function_url($parameters, &$smarty) {
	return Cobweb::get('__RESOLVER__')->reverse($parameters['name'], array_slice($parameters, 1));
}