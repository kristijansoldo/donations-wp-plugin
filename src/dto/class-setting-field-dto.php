<?php

class Setting_Field_Dto {

	public function __construct($name, $label) {
		$this->name = $name;
		$this->label = $label;

		return $this;
	}

	public $name;

	public $label;
}