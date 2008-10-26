<?php

abstract class JSON {
	
	public static function decodeFile($filename) {
	
		if (!file_exists($filename))
			throw new FileNotFoundException("Could not find JSON file '$filename'");
		
		if (($json = file_get_contents($filename)) === false)
			throw new IOException(
				"'file_get_contents($filename)' failed. Insufficient permissions, perhaps?"
			);
		
		try{
			$data = self::decode($json);
		} catch (JSONDecodingException $e) {
			throw new JSONDecodingException(
				"Could not decode JSON file '$filename'. Invalid JSON data, perhaps?"
			);
		}
		return $data;
		
	}
	
	public static function encode($data) {
		$array = array();
		if (is_object($data)) {
			if (method_exists($data, 'toArray')) 
				$array = array_merge($array, $data->toArray());
				
			$array['class'] = get_class($data);
		} else
			$array = $data;
			
		
		if (($json = @json_encode($array)) === false)
			;
			// throw new JSONEncodingException(
			// 	'Could not encode specified data to JSON.');
		
		return $json;
	}
	
	public static function decode($json) {
		$data = json_decode($json, true);
		
		if (!empty($json) && empty($data))
			;
			// throw new JSONDecodingException(
			// 	'Could not decode specified JSON. Invalid JSON data, perhaps?');
			
		return $data;
	}

}


?>