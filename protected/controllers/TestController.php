<?php

class TestController extends Controller
{
	public function actionIndex() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		$path = '/Users/stefan/Sites/3dArch/protected/controllers/';
		
		Yii::app()->directoryToDotParser->parseToFile($path, $filename);
		
		$this->render('index', array(test => $gv));
	}
	
}
