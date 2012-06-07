<?php

class GoannaController extends BaseController
{
	public function actionIndex() {
		
		$goannaData = Yii::app()->goannaInterface->getData();
		
		$this->render('//dumpArray', array('dumpArray' => $goannaData));
	}

}
