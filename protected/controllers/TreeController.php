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
		
		$edgeVisitor = new EdgeVisitor($graphStructure);
		$graphStructure->accept($edgeVisitor);
		
// 		$layout = new LayoutVisitor();
// 		$graphStructure->acceptPostOrder($layout);
		
// 		print_r($layout->path);
		
// 		$this->render('index', array(tree=>$graphStructure));
		
	}
}
