<?php

class NewProjectForm extends CFormModel {

	public $name;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules() {
		return array(
			array('name', 'required'),
			array('name', 'length', 'min' => 3),
		);
	}

}