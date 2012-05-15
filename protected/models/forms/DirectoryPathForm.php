<?php

class DirectoryPathForm extends CFormModel
{
	public $path;

	public function rules()
	{
		return array(
			array('path', 'required'),
		);
	}

}
