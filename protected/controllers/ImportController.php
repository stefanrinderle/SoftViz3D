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
		
		$uploadform = $this->_handleUploadForm();
		
		$this->render('index', array(
                'uploadForm' => $uploadform,
				'directoryPathForm' => $directoryPathform
		));
	}
	
	public function actionSimpleTree() {
		$this->_copyExampleFile(Yii::app()->basePath . '/data/exampleFiles/simpleTree.dot');
	}
	
	public function actionSimpleGraph() {
		$this->_copyExampleFile(Yii::app()->basePath . '/data/exampleFiles/simpleGraph.dot');
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
	
	private function _handleUploadForm() {
		$uploadform = new CForm('application.views.import._uploadForm', new FileUpload());
		
		if ($uploadform->submitted('submit') && $uploadform->validate()) {
			$uploadform->model->dotFile = CUploadedFile::getInstance($uploadform->model, 'dotFile');
			
			$fileName = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
			
			if ($uploadform->model->dotFile->saveAs($fileName)) {
				Yii::app()->user->setFlash('success', 'File successful imported.');
			} else {
				Yii::app()->user->setFlash('error', 'Target file not writeable. ' . $fileName . ' must be writable by the server.');
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
