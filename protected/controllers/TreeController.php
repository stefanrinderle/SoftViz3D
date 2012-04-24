<?php

class TreeController extends Controller
{

	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');

		// Test tree object
		$drei = new Node("drei", array("eins", "zwei"));

		$fuenf = new Node("fuenf", array($drei, "vier"));

		$neun = new Node("neun", array("sieben", "acht"));
		$elf = new Node("elf", array($neun, "zehn"));

		$tree = new Node("tree", array($fuenf, "sechs", $elf));

		$this->postorder($tree);

		$this->render('index', array(tree=>$tree));
	}

	private function postorder($tree)
	{
		$elements = array();
		if ($tree instanceof Node) {
			foreach ($tree->content as $key => $value) {
				$size = $this->postorder($value);
				
				if ($value instanceof Node) {
					array_push($elements, new Element("Knoten" . rand(0, 100), $size));	
				} else {
					array_push($elements, new Element($value, $size));
				}
			}
			
			$tree->size = $this->calcLayout($elements);
			
			return $tree->size;
		} else {
			printf($tree . "<br />");
				
			return 0.1;
		}
	  
	}
	
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';
	private $layoutFile = '/Users/stefan/Sites/3dArch/x3d/temp.adot';
		
	private function calcLayout($elements) {
		Yii::app()->dotWriter->write($elements, $this->outputFile);
		$result = Yii::app()->dotLayout->layout($this->outputFile, $this->layoutFile);
		
		$graph =  Yii::app()->dotParser->parse($this->layoutFile);
		
		$size = $graph['bb'][2] / 100;
		
		return $size;
	}

}

class Element {
	public $name;
	public $size;

	public function __construct($name, $size) {
		$this->name = $name;
		$this->size = $size;
	}
}

class Node {
	public $label;
	public $content = array();
	public $size;

	public function __construct($label, $content) {
		$this->label = $label;
		$this->content = $content;
	}
}