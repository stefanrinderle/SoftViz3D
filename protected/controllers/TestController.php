<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class TestController extends Controller
{
	public function actionIndex() {
		$this->render('index', array(test => "blablabla"));
	}
	
}
