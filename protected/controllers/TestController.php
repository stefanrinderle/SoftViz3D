<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class TestController extends BaseController
{
	public function actionIndex() {
		$this->render('index', array(test => "blablabla"));
	}
	
}
