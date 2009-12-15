<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

function strip_magic_quotes($v) {
	if (!get_magic_quotes_gpc() && !get_magic_quotes_runtime())
		return $v;
	
    return is_array($v) ? 
           array_map('strip_magic_quotes', $v) : 
           stripslashes($v);
}

/**
 * Calls the specified callable with arguments (if any) and returns any resulting
 * output from the callable as a string.
 *	
 *  $output = call_with_output_buffering(function($s) { echo $s; }, 'foo');
 *  // $output === 'foo'
 *  
 * @param callback $callable
 * @param mixed $arguments...
 * @return string
 */
function call_with_output_buffering($callable, $arguments = array()) {
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
 * 	$url = http://acme.com/buy-acme;
 *  $tag_uri = tag_uri($url, CWDateTime::create('2009-05-27'));
 *  // $tag_uri === 'tag:acme.com,2009-05-27:/buy-acme'
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
	$SAFE_CHARACTERS = array(
		'/', '#', '%', '[', ']', '=', ':', ';', '$',
		 '&', '(', ')', '+', ',', '!', '?', '*'
	);
	$SAFE_CHARACTERS_RE = '/([\/#%\\[\\]\\=\\:;\\$&\\(\\)\\+,\\!\\?\\*])/';
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

function identity_function($x) {
	return $x;
}

function empty_function() {
	return NULL;
}

// translation
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
if (!function_exists('__n')) {
	if (!function_exists('ngettext')) {
		function __n($message, $message_plural, $count) {
			return $message;
		}
	} else {
		function __n($message, $message_plural, $count) {
			return ngettext($message, $message_plural, $count);
		}
	}
}


/**
 * Encodes reserved HTML characters (<, >, &, ", ') in the specified `$value`
 * into entities using {@link htmlspecialchars()} with UTF-8 as the default 
 * character set.
 * 
 * @param string $value
 * @param string $character_set
 */
function html_escape($value, $character_set = 'UTF-8') {
	return htmlspecialchars($value, ENT_QUOTES, $character_set);
}

/**
 * Converts an array of key/value pairs into a string of HTML attributes. Unless
 * `$trimmed` is specified, a leading space is included for easy HTML embedding.
 * 
 *	$attrs = html_flatten_attributes(array(
 *  	'type' => 'input'
 *  	'class' => array('required', 'hurricane'),
 *  	'value' => '"El Ñino"'
 *  ));
 *  $element = "<input{$attrs} />";
 *  // $element === <input type="input" class="required hurricane" value="&quot;El Ñino&quot;" />
 * 
 * @param string $value
 * @param string $character_set
 * @param bool $trimmed
 */
function html_flatten_attributes($attributes, $character_set = 'UTF-8', $trimmed = false) {
	$html_attributes = '';
	foreach ($attributes as $key => $value) {
		if (is_int($key)) continue;
		if (is_array($value)) $value = implode(' ', $value);
		$escaped_value = html_escape($value, $character_set);
		$html_attributes .= sprintf(' %s="%s"', $key, $escaped_value);
	}
	return $trimmed ? trim($html_attributes) : $html_attributes;
}
