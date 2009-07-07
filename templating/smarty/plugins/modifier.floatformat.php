<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

function smarty_modifier_floatformat($string, $decimals = 0) {
	return sprintf("%.{$decimals}f", floatval($string));
	
}