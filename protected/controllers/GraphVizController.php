<?php

class GraphVizController extends Controller
{
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');
		
		$this->render('index');
	}
	
	public function actionDotToArray() {
		$dotfile = '/Users/stefan/Sites/3dArch/x3d/parser.dot';
		$outputfile = '/Users/stefan/Sites/3dArch/x3d/parser.adot';
	
		$result = Yii::app()->dotLayout->layout($dotfile, $outputfile);
		
		if (!empty($result)) {
			$this->render('error', array(error=>$result));
		} else {
			$graph =  Yii::app()->dotParser->parse($outputfile);
			
			$this->render('dotToArray', array(input=>$dotfile, 
										 graph=>$graph));
		}
	}
	
	public function actionDirectory() {
		$path = "/Users/stefan/Sites/3dArch/protected/views/";
		
		$result = Yii::app()->xToDotParser->parseDirectoryToArray($path);
		
		$dotfile = Yii::app()->xToDotParser->createDotFile($result);
		
		$this->render('directory', array(directoryStructure=>$result, dotFile=>$dotfile));
	}
}