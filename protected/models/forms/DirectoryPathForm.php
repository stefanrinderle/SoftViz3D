<?php

class DirectoryPathForm extends CFormModel {
	public $path;
	public $includeDot;

	public function rules() {
		return array(
			array('path', 'required'),
			array('includeDot', 'required'),
		);
	}
	
	public function attributeLabels() {
		return array(
				'includeDot'=>'include .XXX files and folders',
		);
	}

}
