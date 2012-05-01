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

		$graphStructure = $this->getSourceDotFileArray();
		
		$this->doLayout($graphStructure, true);
		
		$this->render('index', array(tree=>$graphStructure));
		
// 		$this->render('../dumpGraphStructure', array(graph=>$graphStructure));
		
// 		$this->render('../dumpArray', array(dumpArray=>$array));
	}
	
	private function getSourceDotFileArray() {
		$sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
		
		$dot = Yii::app()->dotParser->parse($sourceFile);
		
// 		$this->render('../dumpArray', array(dumpArray=>$dot));
// 		die();
		return $dot;
	}

	private function doLayout($node, $isMain=false) {
		if ($node instanceof Node) {
			$depth++;
	
			$layoutElements = array();
			foreach ($node->content as $key => $value) {
				//NODES OR LEAF OBJECTS
				$element = $this->doLayout($value, $depth);
				array_push($layoutElements, $element);
			}
	
			// create graph array
			$graph = $this->calcLayout($layoutElements);
	
			// generate x3d code for this layer
			//TODO MAXDEPTH
			$node->x3dInfos = Yii::app()->x3dCalculator->calculate($graph, $depth, 0);
	
			$node->isMain = $isMain;
			// 			$node->depth = $depth;
			// size of the node is the size of its bounding box
			$node->size = array(width=>$graph['bb'][2] / self::$SCALE, height=>$graph['bb'][3] / self::$SCALE);
	
			return $node;
		} else if ($node instanceof Leaf) {
			$node->size = array(width=>0.1, height=>0.1);
			return $node;
		} else {
			print_r("KAPUTT");
			die();
			// 			return array(width=>0.2, height=>0.2);
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
	
// 	private function buildDependencyTree($dotArray, $depth) {
// 		$tree = new Node($value[label], array());
		
// 		$depth++;
		
// 		if ($depth > $this->maxDepth) {
// 			$this->maxDepth = $depth;
// 		}
		
// 		foreach ($dotArray[subgraph] as $key => $value) {
// 			$content = $this->buildDependencyTree($value, $depth);
// 			$node = new Node($value[label], $content);
// 			array_push($tree->content, $node);
// 		}
		
// 		foreach ($dotArray[nodes] as $key => $value) {
// 			$leaf = new Leaf($value[label]);
// 			array_push($tree->content, $leaf);
// 		}
		
// 		foreach ($dotArray[edges] as $key => $value) {
// 			$in = false;
// 			$out = false;
// 			if (array_key_exists($value[out], $dotArray[nodes])) {
// 				$out = true;
// 			}
			
// 			if (array_key_exists($value[in], $dotArray[nodes])) {
// 				$in = true;
// 			}
			
// 			if ($out && $in) {
// 				array_push($tree->content, new Edge($key, $value[in], $value[out]));
// 			}
// 		}
		
// 		return $tree;
// 	}
	
// 	private function buildTree($object, $depth) {
// 		$depth++;
		
// 		if ($depth > $this->maxDepth) {
// 			$this->maxDepth = $depth;
// 		}
		
// 		if (is_array($object)) {
// 			$array = array();
// 			foreach ($object as $key => $value) {
// 				array_push($array, $this->buildTree($value, $depth));
// 			}
// 			return new Node(rand(0, 10000), $array);
// 		} else {
// 			return $object;
// 		}
// 	}
	
}
