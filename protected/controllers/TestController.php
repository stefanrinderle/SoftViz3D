<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class TestController extends BaseController
{
	public function actionIndex() {
		
		$user = User::model()->findByPk(3);
		
		$project = $user->projects[0];
		
		$this->render('//dumpArray', array('dumpArray' => array()));
	}
	
}
