<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class ProjectController extends BaseController {
	
	public function actionIndex() {
		
		$user = Yii::app()->user;
		
		$projects = Project::model()->findAllByAttributes(array('userId'=>$user->getId()));
		
		$this->render('index', array('projects' => $projects));
	}
	
	public function actionNew() {
		$model = new NewProjectForm();
		
		if(isset($_POST['NewProjectForm'])) {
			$model->attributes = $_POST['NewProjectForm'];
				
			if($model->validate()) {
				$project = new Project();
				$project->name = $model->name;
				$project->userId = Yii::app()->user->getId();
				
				$project->save();
		
				$this->redirect(array('project/index'));
				
			}
		}
		$this->render('newProject', array('model' => $model));
	}
}
