<?php

Yii::import('application.extensions.*');
require_once('GraphObjects.php');

class TreeController extends Controller
{
	private static $SCALE = 72;
	
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';
	
	private $maxDepth = 0;
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');

		$graphStructure = Yii::app()->dotParser->parse('/Users/stefan/Sites/3dArch/x3d/dependency.dot');
		
		$layout = new LayoutVisitor();
		$graphStructure->accept($layout);
		
		$this->render('index', array(tree=>$graphStructure));
		
	}
}
