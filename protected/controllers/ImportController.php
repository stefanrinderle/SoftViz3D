<?php

class ImportController extends Controller
{
	function getTime()
	{
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function actionIndex() {
		$directoryPathform = $this->_handleDirectoryPathForm();
		
		$uploadDotForm = $this->_handleUploadDotForm();
		
		$uploadJDependForm = $this->_handleUploadJDependForm();
		
		$this->render('index', array(
                'uploadDotForm' => $uploadDotForm,
				'uploadJDependForm' => $uploadJDependForm,
				'directoryPathForm' => $directoryPathform
		));
	}
	
	public function actionSimpleTree() {
		$this->_copyExampleFile(Yii::app()->basePath . '/data/exampleFiles/simpleTree.dot');
	}
	
	public function actionSimpleGraph() {
		$this->_copyExampleFile(Yii::app()->basePath . '/data/exampleFiles/simpleGraph.dot');
	}
	
	public function actionMvc() {
		$this->_copyExampleFile(Yii::app()->basePath . '/data/exampleFiles/mvc.dot');
	}
	
	private function _copyExampleFile($sourceFileName) {
		$result = copy($sourceFileName, Yii::app()->basePath . Yii::app()->params['currentResourceFile']);
		
		if ($result) {
			Yii::app()->user->setFlash('success', 'File successful imported.');
		} else {
			Yii::app()->user->setFlash('error', 'File could not be loaded.');
		}
		
		$this->render('index', array());
	}
	
	private function _handleUploadDotForm() {
		$uploadform = new CForm('application.views.import._uploadDotForm', new DotFileUpload());
		
		if ($uploadform->submitted('submit') && $uploadform->validate()) {
			$uploadform->model->inputFile = CUploadedFile::getInstance($uploadform->model, 'inputFile');
			
			$fileName = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
			
			if ($uploadform->model->inputFile->saveAs($fileName)) {
				Yii::app()->user->setFlash('success', 'File successful imported.');
			} else {
				Yii::app()->user->setFlash('error', 'Target file not writeable. ' . $fileName . ' must be writable by the server.');
			}
		}
		
		return $uploadform;
	}
	
	private function _handleUploadJDependForm() {
		$uploadform = new CForm('application.views.import._uploadJDependForm', new JDependFileUpload());
	
		if ($uploadform->submitted('submit') && $uploadform->validate()) {
			$uploadform->model->inputFile = CUploadedFile::getInstance($uploadform->model, 'inputFile');
	
			$inputFile = $uploadform->model->inputFile;
			$outputFile = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];

			$result = Yii::app()->jdependToDotParser->parseToFile($inputFile->tempName, $outputFile); 
			
			if ($result) {
				Yii::app()->user->setFlash('success', 'File successful imported.');
			} else {
				Yii::app()->user->setFlash('error', 'File not valid.');
			}
		}
	
		return $uploadform;
	}
	
	private function _handleDirectoryPathForm() {
		$directoryPathform = new CForm('application.views.import._directoryPathForm', new DirectoryPathForm());
	
		if ($directoryPathform->submitted('submit') && $directoryPathform->validate()) {
			$path = $directoryPathform->model->path;
			
			$includeDot = $directoryPathform->model->includeDot;
			if (is_dir($path)) {
				
				$outputFile = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
				Yii::app()->directoryToDotParser->parseToFile($path, $outputFile, $includeDot);
				
				Yii::app()->user->setFlash('success', 'Path accepted - Dot file generation finished');
			} else {
				Yii::app()->user->setFlash('error', 'Error. "' . $path . '" is not a valid server path.');
			}
		}
	
		return $directoryPathform;
	}
	
	
}
