<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class TestController extends BaseController
{
	public function actionIndex() {
		/* reset database */
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		$result = Yii::app()->bestDotParser->parse($filename);
		
		Yii::app()->dotArrayToDB->save($result);
		
		$this->render('//dumpArray', array(dumpArray => $result));
	}
	
}
