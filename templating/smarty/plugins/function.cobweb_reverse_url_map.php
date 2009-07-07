<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Templating
 * @version    $Rev$
 */
function smarty_function_cobweb_reverse_url_map($parameters, &$smarty) {

	$map = array();
	$reverse = Cobweb::get('__RESOLVER__')->reverseMap();
	foreach ($reverse as $a => $p)
		$map[$a] = preg_to_jsregexp($p);

	return JSON::encode($map);
	
}

function preg_to_jsregexp($preg) {
	return preg_replace('/(\()(\?P?<\w+>)(.*?\))/', '$1$3', $preg);
}