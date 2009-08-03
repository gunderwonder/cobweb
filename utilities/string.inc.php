<?php
/**
 * String utility functions
 * 
 * @package Cobweb
 * @subpackage Utilities
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 */

/**
 * Strip trailing occurence of the specified <var>$postfix</var> from a string
 * 
 * <code>
 * $a = rstrip('Cobweb', 'web'); // $a is now 'Cob'
 * </code>
 * 
 * @param string  $string   the string to process
 * @param string  $postfix  the postfix to strip from the string
 */
function rstrip($string, $postfix) {
	if (empty($string) || empty($postfix))
		return $string;
		
	$position = strpos($string, $postfix);
	if ($position === false)
		return $string;
	
	return substr($string, 0, $position);
}

/**
 * Strip leading occurence of the specified <var>$prefix</var> from a string
 * 
 * @param string  $string   the string to process
 * @param string  $postfix  the postfix to strip from the string
 */
function lstrip($string, $prefix) {
	if (empty($string) || empty($prefix))
		return $string;

	$position = utf8_strpos($string, $prefix);
	if ($position === false)
		return $string;

	return utf8_substr($string, utf8_strlen($prefix));
}

function str_starts_with($string, $prefix) {
	if (empty($string) || empty($prefix))
		return false;

	return utf8_strpos($string, $prefix) === 0;
}

function str_ends_with($string, $postfix) {
	if (empty($string) || empty($postfix))
		return false;
	
	return utf8_strrpos($string, $postfix) === 
	       utf8_strlen($string) - utf8_strlen($postfix);
}

function str_contains($string, $substring) {
	return utf8_strpos($string, $substring) !== false;
}


function strip_magic_quotes($v) {
	if (!get_magic_quotes_gpc() && !get_magic_quotes_runtime())
		return $v;
	
    return is_array($v) ? 
           array_map('strip_magic_quotes', $v) : 
           stripslashes($v);
}

function http_parse_qvalues($qvalues) {
	$values = array();
	foreach (preg_split('/\s*,\s*/', $qvalues) as $qvalue) {
		@list($value, $q) = preg_split('/\s*;\s*q\s*=\s*/', $qvalue);
		$q = (is_null($q) || !is_numeric($q)) ? 1.0 : floatval($q);
		$values[$q][] = $value;
	}
	krsort($values, SORT_NUMERIC);
	return $values;
}

if (!function_exists('__')) {
	if (!function_exists('_')) {
		function __($message) {
			return $message;
		}
	} else {
		function __($message) {
			return _($message);
		}
	}
}