<?php

class ImportController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column2';
	
	function getTime()
	{
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function actionIndex() {
		$directoryPathform = $this->_handleDirectoryPathForm();
		
		$uploadform = $this->_handleUploadForm();
		
		$this->render('index', array(
                'uploadForm' => $uploadform,
				'directoryPathForm' => $directoryPathform
		));
		
	}
	
	private function _handleUploadForm() {
		$uploadform = new CForm('application.views.import._uploadForm', new FileUpload());
		
		if ($uploadform->submitted('submit') && $uploadform->validate()) {
			$uploadform->model->dotFile = CUploadedFile::getInstance($uploadform->model, 'dotFile');
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//do something with your image here
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			Yii::app()->user->setFlash('success', 'File Uploaded');
			//$this->redirect(array('upload'));
		}
		
		return $uploadform;
	}
	
	private function _handleDirectoryPathForm() {
		$directoryPathform = new CForm('application.views.import._directoryPathForm', new DirectoryPathForm());
	
		if ($directoryPathform->submitted('submit') && $directoryPathform->validate()) {
			$directoryPathform->model->path = CUploadedFile::getInstance($directoryPathform->model, 'path');
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//do something with your image here
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			Yii::app()->user->setFlash('success', 'Path accepted');
			//$this->redirect(array('upload'));
		}
	
		return $directoryPathform;
	}
	
}
