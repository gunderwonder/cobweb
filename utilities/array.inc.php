<?php

function array_starts_with($array, $start) {
	
	for ($i = 0; $i < count($array); $i++) {
		if ($i != 0 && !isset($start[$i]))
			return true;
		
		if ($array[$i] != $start[$i])
			return false;
	}
	return true;
}

function array_lstrip($array, $start) {

	$new_array = array();
	for ($i = 0; $i < count($array) && $i < count($start); $i++) {
		
		if (!isset($start[$i]))
			return $new_array;
		else if ($array[$i] == $start[$i])
			$new_array[] = $array[$i];
			
	}
	return $new_array;
}

function array_without_keys($array, $keys) {

	$without = array();
	foreach ($array as $key => $value)
		if (!in_array($key, $keys))
			$without[$key] = $value;
	return $without;
}

function array_without_indices($array, $keys) {
	$without = array();
	for ($i = 0; $i < count($array); $i++)
		if (!in_array($i, $keys))
			$without[] = $array[$i];
	return $without;
}

function array_index_of_key($array, $key) {
	$i = 0;
	foreach ($array as $key_ => $value) {
		if ($key == $key_)
			return $i;
		$i++;
	}
	return -1;
}

function array_index_of($array, $value) {
	$i = 0;
	foreach ($array as $value_) {
		if ($value === $value_)
			return $i;
		$i++;
	}
	
}

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
