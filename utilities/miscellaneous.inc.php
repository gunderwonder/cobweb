<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Calls the specified callable with arguments (if any) and returns any resulting
 * output from the callable as a string.
 *  
 * @param callback $callable
 * @param mixed $arguments...
 * @return string
 */
function call_with_output_buffering($callable, $arguments) {
	$arguments = func_get_args();
	array_shift($arguments);	
	ob_start();
		call_user_func_array($callable, $arguments);
		$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

/**
 * Creates a TagURI from a URL and a {@link DateTime} object
 * 
 * @see http://code.djangoproject.com/browser/django/tags/releases/1.1.1/django/utils/feedgenerator.py#L48
 * @see http://diveintomark.org/archives/2004/05/28/howto-atom-id
 * @see http://www.faqs.org/rfcs/rfc4151.html
 *  
 * @param string $url
 * @param DateTime $date
 * @param string $dateformat
 */
function tag_uri($url, $date, $dateformat = 'Y-m-d') {
	$tag_uri = preg_replace('/^\w+:\/\//', '', $url, 1, $count);
	if ($count != 1 && $tag_uri[0] == '/')
		throw new InvalidArgumentException("URL '{$url}' lacks hostname");
	$tag_uri = str_replace('#', '/', $tag_uri);
	if ($date instanceof DateTime) {
		if (!in_array($dateformat, array('Y-m-d', 'Y-m', 'Y')))
			throw new InvalidArgumentException("Invalid date format {$dateformat}");
		$formatted_date = $date->format($dateformat);
		$tag_uri = preg_replace('/\//', ",{$formatted_date}:/", $tag_uri, 1);
	}
	return 'tag:' . $tag_uri;
}

/**
 * Converts a UTF-8 IRI to an all-ASCII URI without escaping reserved URI characters
 * 	iri_to_uri('/cities/Paris & Orléans') // => /cities/Paris%20&%20Orl%C3%A9ans
 *  
 * @see http://code.djangoproject.com/browser/django/tags/releases/1.1.1/django/utils/encoding.py#L123
 */
function iri_to_uri($iri) {
	$SAFE_CHARACTERS = array('/', '#', '%', '[', ']', '=', ':', ';', '$', '&', '(', ')', '+', ',', '!', '?', '*');
	$SAFE_CHARACTERS_RE = '{([/#%\\[\\]\\=\\:;\\$&\\(\\)\\+,\\!\\?\\*])}';
	$parts = preg_split($SAFE_CHARACTERS_RE, $iri, -1, PREG_SPLIT_DELIM_CAPTURE);
	$uri = '';
	foreach ($parts as $part)
		$uri .= in_array($part, $SAFE_CHARACTERS) ? $part : rawurlencode($part);
	return $uri;
}

/**
 * Takes the value of an HTTP content negotiation header and returns an 
 * array of header values types ordered and indexed by their quality value
 *  
 * @param string $qvalues
 * @return retu
 */
function http_parse_qvalues($qvalues) {
	$values = array();
	foreach (preg_split('/\s*,\s*/', $qvalues) as $qvalue) {
		@list($value, $q) = preg_split('/\s*;\s*q\s*=\s*/', $qvalue);
		$q = (is_null($q) || !is_numeric($q)) ? 1.0 : floatval($q);
		$values[(string)$q][] = $value;
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
