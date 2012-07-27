<?php

class ImportController extends BaseController {
	
	public function actionIndex($projectId) {
		$uploadDotForm = $this->_handleUploadDotForm($projectId);

		$uploadJDependForm = $this->_handleUploadJDependForm($projectId);
		
		$directoryPathform = $this->_handleDirectoryPathForm();
				
		$this->render('index', array(
                'uploadDotForm' => $uploadDotForm,
				'uploadJDependForm' => $uploadJDependForm,
				'directoryPathForm' => $directoryPathform,
				'projectId' => $projectId
		));
	}
	
	public function actionGoanna($file, $projectId) {
		$file = "goanna/" . $file;
		
		$this->actionExampleFile($file, $projectId);
	}
	
	public function actionExampleFile($file, $projectId) {
		$project = Project::model()->findByPk($projectId);
		
		$filePath = Yii::app()->basePath . '/data/exampleFiles/' . $file;
		$fp = fopen($filePath, 'r');
		$fileContent = fread($fp, filesize($filePath));
		//TODO:????
		//$content = addslashes($content);
		fclose($fp);

		$this->saveFile($projectId, $fileContent);
		
		$this->redirect(array('project/index'));
	}

	private function _handleUploadDotForm($projectId) {
		$uploadform = new CForm('application.views.import._uploadDotForm', new DotFileUpload());
		
		if ($uploadform->submitted('submit') && $uploadform->validate()) {
			$fileContent = CUploadedFile::getInstance($uploadform->model, 'inputFile');
			
			$this->saveFile($projectId, $fileContent);
			
			$this->redirect(array('project/index'));
		}	
		
		return $uploadform;
	}
	
	private function _handleUploadJDependForm($projectId) {
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
	
	private function saveFile($projectId, $fileContent) {
		$project = Project::model()->findByPk($projectId);
			
		if ($project && $project->userId == Yii::app()->user->getId()) {
			$project->file = $fileContent;
			$project->setFileUpdateTime(new DateTime());
	
			$project->save();
	
			Yii::app()->user->setFlash('success', 'File successful imported.');
		} else {
			Yii::app()->user->setFlash('error', 'File could not be loaded.');
		}
	}
}
