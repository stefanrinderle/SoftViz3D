<?php

Yii::import('application.extensions.*');
require_once('GraphObjects.php');

class TreeController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');

		$graphStructure = Yii::app()->dotParser->parse($this->sourceFile);
		
		print_r($graphStructure);
		
		$layout = new LayoutVisitor();
		$graphStructure->acceptPostOrder($layout);
		
// 		$this->render('index', array(tree=>$graphStructure));
		
	}
}
