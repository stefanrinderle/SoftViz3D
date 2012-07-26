<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class ProjectController extends BaseController {
	
	public function actionIndex() {
		
		$user = Yii::app()->user;
		
		$projects = Project::model()->findAllByAttributes(array('userId'=>$user->getId()));
		
		$this->render('index', array('projects' => $projects));
	}
	
}
