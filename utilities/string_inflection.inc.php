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

	$slug = preg_replace('/[,!\?:;=\(\)\/\\&\$\"]/u', '', trim($slug));
	$slug = preg_replace('/([\._\s\']+)/u', '-', $slug);
	$slug = preg_replace('/-+/u', '-', $slug);
	
    $slug = htmlentities(trim($slug), ENT_NOQUOTES, 'UTF-8');
    $slug = strtolower($slug);
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


function stringify($object, $level = 1) {
	static $seen = array();
	
	if (is_null($object))
		return 'NULL';
	
	if (is_bool($object))
		return $object ? 'true' : 'false';
	
	if (is_object($object)) {
		$class = get_class($object);
		
		if (in_array($object, $seen))
			return "< $class >";
		$seen[] = $object;
		$vars = get_object_vars($object);
		
		if (method_exists($object, 'toArray'))
			$vars = $object->toArray();

		else if (method_exists($object, '__toString'))
			$vars = "{$object->__toString()}";
		
		$indent = !empty($vars) ? "\n" . str_repeat("\t", $level) : '';
		$vars = stringify($vars);
		$seen = array();
		$level = 1;
		return "< {$class} ⇒ {$indent}{$vars} >";
	
	} else if (is_array($object)) {
		if (empty($object))
			return '[]';
		$array = "[\n" . str_repeat("\t", $level);
		for ($i = 0; $i < count($object); $i++) {
			if (!is_int(key($object)))
				$array .= ' ' . key($object) . ' → ' . stringify(current($object), $level + 1);
			else
				$array .= ' ' . stringify(current($object), $level + 1)	;
				
			if ($i != count($object) - 1)
				$array .= ",\n" . str_repeat("\t", $level);
			
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
