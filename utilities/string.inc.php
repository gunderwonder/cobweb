<?php
/**
 * String utility functions, baby!
 * 
 * @package Cobweb
 * @subpackage Utilities
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 */

/**
 * Strip trailing occurence of the specified `$postfix` from a string
 * 
 * 	$a = rstrip('Cobweb', 'web'); // $a is now 'Cob'
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
 * Strip leading occurence of the specified `$prefix` from a string
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

/**
 * Returns true if `$string` starts with `$prefix`, false otherwise
 *  
 * @param string $string
 * @param string $prefix
 * @return bool
 */
function str_starts_with($string, $prefix) {
	if (empty($string) || empty($prefix))
		return false;

	return utf8_strpos($string, $prefix) === 0;
}

/**
 * Returns true if `$string` ends with `$prefix`, false otherwise
 *  
 * @param string $string
 * @param string $postfix
 * @return bool
 */
function str_ends_with($string, $postfix) {
	if (empty($string) || empty($postfix))
		return false;
	return utf8_strrpos($string, $postfix) === 
	       utf8_strlen($string) - utf8_strlen($postfix);
}

/**
 * Returns true if `$substring` is a substring of `$string`, false otherwise
 *  
 * @param string $string
 * @param string $substring
 * @return bool
 */
function str_contains($string, $substring) {
	return utf8_strpos($string, $substring) !== false;
}

/**
 * Returns true if `$substring` is a substring of `$string`, false otherwise
 *  
 * @param string $string
 * @param string $substring
 * @return bool
 */
function in_string($substring, $string) {
	return utf8_strpos($string, $substring) !== false;
}

/**
 * Returns `$string` with the first character in uppercase.
 *  
 * @param string $string
 * @param string $substring
 * @return bool
 */
function utf8_ucfirst($string) {
	if (empty($string)) return $string;
	$first = utf8_strtoupper(utf8_substr($string, 0, 1));
	return $first . utf8_substr($string, 1);
}