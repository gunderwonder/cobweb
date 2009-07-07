<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

function str_classify($string) {
	return preg_replace_callback('/[_-](\w)/',
		create_function('$x', 'return ucfirst($x[1]);'),
		ucfirst($string)
	);
}

/**
 * @see http://www.mail-archive.com/django-users@googlegroups.com/msg03402.html
 */
function str_slugify($string) {
    
    $slug = strip_tags($string);

	$strip = array(
		',', '-', '.', '!', '?', ':', ';', '=', 
		'(', ')', '/', '\\', '&', '$', '\'', '"'
	);
	$slug = str_replace($strip, '', $slug);    
	
    $slug = htmlentities(trim($slug), ENT_NOQUOTES, 'UTF-8');
    $slug = strtolower(preg_replace('/[_\s\.\'"]+/u', '-', trim($slug)));
  	$slug = preg_replace(
    	'/&(\w+)(uml|acute|grave|circ|tilde|slash|ring|lig);/',
    	'$1',
    	$slug
	);
    
  	return urlencode(html_entity_decode($slug, ENT_COMPAT, 'UTF-8'));
}

function str_camelize($string) {
	$string = str_classify($string);
	$string[0] = strtolower($string[0]);
	return $string;
}

function str_titlecase($string) {
	return ucfirst($string);
}


function stringify($object) {
	if (is_object($object)) {
		$class = get_class($object);
		$vars = get_object_vars($object);
		
		if (method_exists($object, 'toArray'))
			$vars = $object->toArray();

		else if (method_exists($object, '__toString'))
			$vars = "{$object->__toString()}";
			
		$vars = stringify($vars);
		return "< $class ⇒ $vars >";
	
	
	} else if (is_array($object)) {
		$array = '[';
		for ($i = 0; $i < count($object); $i++) {
			if (!is_int(key($object)))
				$array .= ' ' . key($object) . ' → ' . stringify(current($object));
			else
				$array .= ' ' . stringify(current($object))	;
				
			if ($i != count($object) - 1)
				$array .= ',';
			
			next($object);
		}
			
		$array .= ' ]';
		return $array;
		
	} else if (is_string($object)) {
		if (strlen($object) > 30)
			$object = substr($object, 0, 30) . ' (…)';
		return "'" . $object . "'";
	}
	
	return (string)$object;
}