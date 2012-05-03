<?php

abstract class GraphVisitor {
	abstract function visitLayer(Layer $comp, $level, $layoutElements);
	abstract function visitLeaf(Leaf $comp, $level);
}

class LayoutVisitorBak extends GraphVisitor {
	private static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';

	private $max_level = 0;

	function visitLayer(Layer $comp, $level, $layoutElements) {
// 		if ($comp->label == "MAIN_NODE") {
// 			print_r($layoutElements);
// 			die();
// 		}
		
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

	public $parent;

	abstract function acceptPostOrder(GraphVisitor $visitor, $level);

	public function __construct($label, $parent) {
		$this->label = $label;
		$this->parent = $parent;
	}
}

/**
 * representates the node itself and the layout information for the layer beyond
 */
class Layer extends GraphComponent {
	public $content = array();
	public $x3dInfos;

	public $isMain;
	// 	public $depth;

	public $edges = array();

	public $outEdgeCount = 0;
	public $inEdgeCount = 0;

	function acceptPostOrder(GraphVisitor $visitor, $level = 1) {
		$this->level = $level;

		$layoutElements = array();
		foreach ($this->content as $child) {
			$element = $child->acceptPostOrder($visitor, $level + 1);
			array_push($layoutElements, $element);
		}

		
		foreach ($this->edges as $edge) {
			$isStart = $this->isInLayer($edge->out, $this->content);
			$isEnd = $this->isInLayer($edge->in, $this->content);
				
			if ($isStart && $isEnd) {
				array_push($layoutElements, $edge);
			} else {
				// create the dependency hole for this node
				$random = rand(0, 10);
				$leaf = new Leaf("dHole" . $random, $this);
				array_push($layoutElements, $leaf->acceptPostOrder($visitor,  $level + 1));
			} 
				
			if ($isStart) {
				array_push($layoutElements, new Edge("dEdge", $edge->out, "dHole" . $random));
				// now process the path to the end
// 				print_r("<br />");
// 				print_r($this->parent);
// 				print_r($this->content[0]->label);
				
// 				print_r("node_3");
				
				// TODO: find the destination node via adding objects and not labels into edge constructor
// 				$depEdgeArray = $this->commonAncestor($this->content[0]->content[0], $this->content[1]);
				
// 				print_r("HERE:" . $this->label);
// 				print_r($depEdgeArray);
				
// 				$layoutElements = array_merge($layoutElements, $depEdgeArray);
			} else if ($isEnd) {
				array_push($layoutElements, new Edge("dEdge", "dHole" . $random, $edge->in));
			}
		}

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

	function accept(EdgeVisitor $visitor, $level = 1) {
		return $visitor->visitEdge($this, $level);
	}
	
	function acceptPostOrder(GraphVisitor $visitor, $level = 1) {
		return $visitor->visitEdge($this, $level);
	}
}
?>