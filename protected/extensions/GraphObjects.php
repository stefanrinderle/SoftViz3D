<?php

abstract class GraphVisitor {
	abstract function visitLayer(Layer $comp, $level, $layoutElements);
	abstract function visitLeaf(Leaf $comp, $level);
}

class LayoutVisitor extends GraphVisitor {
	private static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';
	
	private $max_level = 0;
	
	function visitLayer(Layer $comp, $level, $layoutElements) {
		// create graph array
		$layerLayout = $this->calcLayerLayout($layoutElements);

		// generate x3d code for this layer
		$comp->x3dInfos = Yii::app()->x3dCalculator->calculate($layerLayout, $level, $this->max_level);
		$comp->isMain = ($level == 1);
		
		// size of the node is the size of its bounding box
		$comp->size = array(width=>$layerLayout['bb'][2] / self::$SCALE, height=>$layerLayout['bb'][3] / self::$SCALE);
		
		return $comp;
	}
	
	function visitEdge(Edge $edge, $level) {
// 		print_r(" VISIT: " . $edge->label);
// 		print_r("<br />");
// 		print_r(" PATH: ");
// 		print_r($this->path);
	}

	function visitLeaf(Leaf $leaf, $level) {
		if ($this->max_level < $level) $this->max_level = $level;
		
		$leaf->size = array(width=>0.1, height=>0.1);
		$leaf->level = $level;	
		return $leaf;
	}

	/**
	 * Writes the current elements in an dot file and generated the layout dot file
	 */
	private function calcLayerLayout($elements) {
		Yii::app()->dotWriter->writeToFile($elements, $this->outputFile);
		$layoutDot = Yii::app()->dotLayout->layout($this->outputFile);
	
		$layout = Yii::app()->adotArrayParser->parse($layoutDot);
	
		return $layout;
	}
}

abstract class GraphComponent {
	public $label;
	public $size;
	
	abstract function acceptPostOrder(GraphVisitor $visitor, $level);
	
	public function __construct($label) {
		$this->label = $label;
	}
}

/**
 * representates the node itself and the layout information for the layer beyond
 */
class Layer extends GraphComponent {
	public $content;
	public $x3dInfos;
	
	public $isMain;
	// 	public $depth;

	public $edges;
	
	public $outEdgeCount = 0;
	public $inEdgeCount = 0;

	public function __construct($label) {
		parent::__construct($label);
		
		$this->content = array();
		$this->edges = array();
	}
	
	function acceptPostOrder(GraphVisitor $visitor, $level = 1) {
		$layoutElements = array();
		foreach ($this->content as $child) {
			$element = $child->acceptPostOrder($visitor, $level + 1);
			array_push($layoutElements, $element);
		}
// 		if (count($this->edges)) {
// 			$random = rand(0, 10);
// 			$leaf = new Leaf("dHole" . $random);
// 			array_push($layoutElements, $leaf->acceptPostOrder($visitor,  $level + 1));
// 		}
// 		foreach ($this->edges as $edge) {
// 			$isStart = $this->isInLayer($edge->out, $this->content);
// 			$isEnd = $this->isInLayer($edge->in, $this->content);
			
// 			if ($isStart && $isEnd) {
// 				array_push($layoutElements, $edge);
// 			} else if ($isStart) {
// 				array_push($layoutElements, new Edge("dEdge", $edge->out, "dHole" . $random));
// 			} else if ($isEnd) {
// 				array_push($layoutElements, new Edge("dEdge", "dHole" . $random, $edge->in));
// 			}
// 		}
		
		return $visitor->visitLayer($this, $level, $layoutElements);
	}

	public function __toString() {
		return "NODE " . $this->label;
	}
	
	private function isInLayer($edgeString, $layerContent) {
		foreach ($layerContent as $value) {
			if ($value instanceOf Leaf) {
				if ($value->label == $edgeString) {
					return true;
				}
			}
		} 
		return false;
	}
}



class Leaf extends GraphComponent {
	
	public function __construct($label) {
		parent::__construct($label);
	}

	function acceptPostOrder(GraphVisitor $visitor, $level = 1) {
		return $visitor->visitLeaf($this, $level);
	}
	
	public function __toString() {
		return "LEAF " . $this->label;
	}
}

class Edge {
	public $label;
	public $in;
	public $out;

	public function __construct($label, $in, $out) {
		$this->label = $label;
		$this->in = $in;
		$this->out = $out;
	}
	
	function acceptPostOrder(GraphVisitor $visitor, $level = 1) {
		return $visitor->visitEdge($this, $level);
	}
}
?>