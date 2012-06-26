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
		
		Yii::app()->ownDotParser->parse($filename);
		
		$this->render('//dumpArray', array(dumpArray => array()));
	}
	
}
