<?php

class GraphVizController extends Controller
{
	
	private $dotfile = '/Users/stefan/Sites/3dArch/x3d/simpleGraph2D.dot';
	private $outputfile = '/Users/stefan/Sites/3dArch/x3d/simpleGraph2D.adot';
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');
		
		$result = Yii::app()->dotLayout->layout($this->dotfile, $this->outputfile);
		
		if (!empty($result)) {
			$this->render('error', array(error=>$result));
		} else {
			$graph =  Yii::app()->dotParser->parse($this->outputfile);
			
			$this->render('index', array(input=>$this->dotfile, 
										 graph=>$graph));
		}
	}
}