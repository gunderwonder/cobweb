<?php


class TestForm extends Form {
	
	public function configure() {
		$this->username = new TextField();
		$this->number = new IntegerField();
		$this->negative_number = new IntegerField();
	}
}