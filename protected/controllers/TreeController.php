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
		
		$this->doLayout($graphStructure, 0, true);
		
		$this->render('index', array(tree=>$graphStructure));
		
// 		$this->render('../dumpGraphStructure', array(graph=>$graphStructure));
	}
	
	private function doLayout($node, $depth, $isMain=false) {
		if ($node instanceof Node) {
			$depth++;
	
			$layoutElements = array();
			foreach ($node->content as $key => $value) {
				//NODES OR LEAF OBJECTS
				$element = $this->doLayout($value, $depth);
				array_push($layoutElements, $element);
			}
			
			foreach ($node->flatEdges as $key => $value) {
				//NODES OR LEAF OBJECTS
				//$element = $this->doLayout($value, $depth);
				array_push($layoutElements, $value);
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
	
}
