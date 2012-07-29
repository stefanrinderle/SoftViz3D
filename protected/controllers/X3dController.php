<?php

class X3dController extends BaseController {
	
	public $layout='//x3d/layout';
	
	public function actionIndex($layoutId) {
		$layout = Layout::model()->findByPk($layoutId);
		
		$this->render('index', array('layout' => $layout));
	}
	
}
