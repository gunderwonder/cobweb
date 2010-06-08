<?php

class ModelChoiceField extends ChoiceField {
	
	public function __construct($query, array $properties = array()) {
		if (is_string($query)) 
			$query = Model::query($query);
		
		$entities = array();
		$collection = $query->execute();

		foreach ($collection as $entity)
			$entities[$entity->id] = $entity;
		parent::__construct($entities, $properties);
	}
	
}