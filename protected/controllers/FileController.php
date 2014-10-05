<?php

class FileController extends BaseController {

	public function actionIndex($projectId) {
		$project = Project::model()->findByPk($projectId);
		
		$fileArray = $project->getFileStringArray();
		
		$content = $this->createEditorString($fileArray);
		
		$this->render('index', array('fileContent' => $content, 'projectId' => $projectId));
	}
	
	public function actionCheck($projectId) {
		$project = Project::model()->findByPk($projectId);
		
		//$row = mysql_fetch_assoc();
		$data = $project->getFileStringArray();
		
		try {
			$result =Yii::app()->dotArrayParser->parse($data);
			
			Yii::app()->user->setFlash('success', 'File valid');
			
			$this->render('check', array('result' => $result, 'projectId' => $projectId));
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Check failed: ' . $e->getMessage());
			
			$this->render('check', array('exception' => $exception, 'projectId' => $projectId));
		}
	}
	
	public function actionEdit($projectId) {
		$editform = new CForm('application.views.file._editForm', new EditFileForm());
		
		$project = Project::model()->findByPk($projectId);
		
		if ($editform->submitted('submit') && $editform->validate()) {
			$content = $editform->model->content;
		
			if ($project && $project->userId == Yii::app()->user->getId()) {
				$project->saveNewFileString($content);
		
				Yii::app()->user->setFlash('success', 'File saved.');
			} else {
				Yii::app()->user->setFlash('error', 'File could not be saved.');
			}
			
			$this->redirect(array('file/index', 'projectId' => $projectId));
		}
		
		$fileArray = $project->getFileStringArray();
		
		$content = $this->createEditorString($fileArray, false);
		$string = "";
		foreach ($content as $value) {
			$string .= $value . "\n";
		}
		$editform->model->content = htmlspecialchars_decode($string);
		
		$this->render('edit', array(
			'form' => $editform, 'projectId' => $projectId
		));
	}
		
	private function createEditorString($fileArray, $depht = true) {
			$content = array();
			if ($depht) {
				$depthString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			} else {
				$depthString = "";
			}
			
			$depth = "";
		
			foreach ($fileArray as $line) {
				if (strpos($line, "}")) {
					$depth = substr($depth, 0, strlen($depth) - strlen($depthString));
				}
		
				array_push($content, $depth . $line);
		
				if (strpos($line, "{")) {
					$depth .= $depthString;
				}
			}
		
			return $content;
	}
		
}
