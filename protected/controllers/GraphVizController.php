<?php

class GraphVizController extends Controller
{
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');
		
		$this->render('index');
	}
	
	public function actionDirectory() {
		$path = "/Users/stefan/Sites/3dArch/protected/";
		$outputFile = '/Users/stefan/Sites/3dArch/x3d/parser.dot';
		
		Yii::app()->directoryToDotParser2->parse($path, $outputFile);
		
		$fileContent = file($outputFile);
		
		$this->render('directory', array(fileContent=>$fileContent, fileName=>$outputFile));
	}
	
	public function actionDirectoryArray() {
		$path = "/Users/stefan/Sites/3dArch/protected/";
		
		$array = Yii::app()->directoryToDotParser2->parseDirectoryToArray($path);
		
		$this->render('../dumpArray', array(dumpArray=>$array));
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
	
	
}