<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

function smarty_modifier_linkify($string, $nofollow = false) {
	$replacement = $nofollow ? '<a href="$1" rel="nofollow">$1</a>' : '<a href="$1">$1</a>';
	return preg_replace('{(https?://[^\<\>\s]+)}', $replacement, $string);
}