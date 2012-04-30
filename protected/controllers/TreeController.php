<?php

class TreeController extends Controller
{

	private static $SCALE = 72;
	
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';
	
	private $maxDepth = 0;
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');

		// Test tree object
// 		$drei = new Node("drei", array("eins", "zwei"));
// 		$fuenf = new Node("fuenf", array($drei, "vier"));
// // 		$neun = new Node("neun", array("sieben", "acht"));
// // 		$neun2 = new Node("neun2", array("sieben", "acht"));
// // 		$elf = new Node("elf", array($neun, "zehn", $neun2));
// // 		$tree = new Node("tree", array($fuenf, "sechs", $elf));
		
// 		$this->maxDepth = 2;
// 		$tree = $fuenf;
		
// // 		$path = "/Users/stefan/Sites/3dArch/protected/views/";
// // 		$tree = Yii::app()->directoryToDotParser2->getDirectoryTree($path);

// 		$tree = $this->buildTree($tree, 0);
		
// 		// test
// 		$this->postorder($tree, 0, true);

// 		$this->render('index', array(tree=>$tree));

		$this->getSourceDotFile();
	}
	
	private function getSourceDotFile() {
		$sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
		
		$dot = Yii::app()->dotParser->parse($sourceFile);
		
		$this->render('../dumpArray', array(dumpArray=>$dot));
	}

	private function buildTree($object, $depth) {
		$depth++;
		
		if ($depth > $this->maxDepth) {
			$this->maxDepth = $depth;
		}
		
		if (is_array($object)) {
			$array = array();
			foreach ($object as $key => $value) {
				array_push($array, $this->buildTree($value, $depth));
			}
			return new Node(rand(0, 10000), $array);
		} else {
			return $object;
		}
	}
	
	private function postorder($node, $depth, $isMain=false)
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
			
			// create graph array
			$graph = $this->calcLayout($elements);
			
			// generate x3d code for this layer
			$node->x3dInfos = Yii::app()->x3dCalculator->calculate($graph, $depth, $this->maxDepth);

			$node->isMain = $isMain;
			$node->depth = $depth;
			// size of the node is the size of its bounding box
			$node->size = array(width=>$graph['bb'][2] / self::$SCALE, height=>$graph['bb'][3] / self::$SCALE);
			
			return $node->size;
		} else {
			return array(width=>0.1, height=>0.1);
		}
	}

	/**
	 * Writes the current elements in an dot file and generated the layout dot file
	 */
	private function calcLayout($elements) {
		Yii::app()->dotWriter->writeToFile($elements, $this->outputFile);
		
		$layoutDot = Yii::app()->dotLayout->layout($this->outputFile);
		
		$graph = Yii::app()->adotArrayParser->parse($layoutDot);

		return $graph;
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
	public $x3dInfos;
	public $isMain;
	public $depth;

	public function __construct($label, $content) {
		$this->label = $label;
		$this->content = $content;
	}
}