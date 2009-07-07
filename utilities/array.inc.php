<?php

/**
 * Ugh.
 * @deprecated
 */
function array_to_sql_list($iterable, $key = NULL, $quote = false) {
	$list = '(';
	$is_array = is_array($iterable);
	
	for ($i = 0; $i < count($iterable); $i++) {
		$value = $iterable[$i];
		if ($key == NULL)
			$value = $iterable[$i];
		else
			if (is_array($value))
				$value = $iterable[$i][$key];
			else
				$value = $iterable[$i]->$key;

		if ($quote)
			$value = "'{$value}'";
		$list .= $value . ($i != (count($iterable) - 1) ? ', ' : '');		
	}
	
	return $list . ')';
}
