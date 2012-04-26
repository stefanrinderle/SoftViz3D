<?php

class TreeController extends Controller
{

	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';
	private $layoutFile = '/Users/stefan/Sites/3dArch/x3d/temp.adot';

	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');

		// Test tree object
		$drei = new Node("drei", array("eins", "zwei"));

		$fuenf = new Node("fuenf", array($drei, "vier"));
		
		$neun = new Node("neun", array("sieben", "acht"));
		$neun2 = new Node("neun2", array("sieben", "acht"));
		$elf = new Node("elf", array($neun, "zehn", $neun2));

		$tree = new Node("tree", array($fuenf, "sechs", $elf));

		// test
		$this->postorder($tree, 0, true);

		$this->render('index', array(tree=>$tree));
	}

	private function postorder($node, $depth, $main=false)
	{
		$elements = array();
		if ($node instanceof Node) {
			$depth++;
			foreach ($node->content as $key => $value) {
				$size = $this->postorder($value, $depth);

				if ($value instanceof Node) {
					array_push($elements, new Element($value->label, $size, "node"));
				} else {
					array_push($elements, new Element($value, $size, "leaf"));
				}
			}
			
			// write layout dot file
			$node->size = $this->calcLayout($elements);
			
			// generate x3d code for this layer
			$node->x3d = Yii::app()->x3dGenerator->generate($this->layoutFile);
			$node->main = $main;
			
			$node->depth = $depth;
			
			return $node->size;
		} else {
			return new Size(1, 1);
		}
		 
	}

	/**
	 * Writes the current elements in an dot file and generated the layout dot file
	 */
	private function calcLayout($elements) {
		Yii::app()->dotWriter->write($elements, $this->outputFile);
		$result = Yii::app()->dotLayout->layout($this->outputFile, $this->layoutFile);

		$graph =  Yii::app()->dotParser->parse($this->layoutFile);

		return new Size($graph['bb'][2] / 72, $graph['bb'][3] / 72);
	}

}


class Size {
	public $width;
	public $height;

	public function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}
}

class Element {
	public $name;
	public $size;
	public $type;
	
	public function __construct($name, $size, $type) {
		$this->name = $name;
		$this->size = $size;
		$this->type = $type;
	}
}

class Node {
	public $label;
	public $content = array();
	public $width;
	public $height;
	public $x3d;
	public $main;
	public $depth;

	public function __construct($label, $content) {
		$this->label = $label;
		$this->content = $content;
	}
}