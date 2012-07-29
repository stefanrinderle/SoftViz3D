<?php

class X3dController extends BaseController {
	
	public $layout='//layouts/x3d';
	
	public function actionIndex($layoutId) {
		$layout = Layout::model()->findByPk($layoutId);
		
		$this->render('index', array('layout' => $layout));
	}
	
}
