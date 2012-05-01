<?php

abstract class GraphVisitor {
	abstract function visitNode(Node $comp, $level, $layoutElements);
	abstract function visitLeaf(Leaf $comp, $level);
}

class LayoutVisitor extends GraphVisitor {
	private static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';
	
	function visitNode(Node $comp, $level, $layoutElements) {
		// create graph array
		$graph = $this->calcLayout($layoutElements);
		
		// generate x3d code for this layer
		//TODO MAXDEPTH
		$comp->x3dInfos = Yii::app()->x3dCalculator->calculate($graph, $level, 0);
		
		$comp->isMain = ($level == 1);
		// 			$node->depth = $depth;
		// size of the node is the size of its bounding box
		$comp->size = array(width=>$graph['bb'][2] / self::$SCALE, height=>$graph['bb'][3] / self::$SCALE);
		
		return $comp;
	}

	function visitLeaf(leaf $leaf, $level) {
		$leaf->size = array(width=>0.1, height=>0.1);
		return $leaf;
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

class GraphComponent {
	public $label;
	
	public function __construct($label) {
		$this->label = $label;
	}
}

/**
 * representates the node itself and the layout information for the layer beyond
 */
class Node extends GraphComponent {
	public $content;
	public $x3dInfos;
	// 	public $depth;

	public $flatEdges;

	public function __construct($label) {
		parent::__construct($label);
		
		$this->content = array();
		$this->flatEdges = array();
	}
	
	function accept(GraphVisitor $visitor, $level = 1) {
		$layoutElements = array();
		foreach ($this->content as $child) {
			$element = $child->accept($visitor, $level + 1);
			array_push($layoutElements, $element);
		}
		foreach ($this->flatEdges as $child) {
			array_push($layoutElements, $child);
		}
		
		return $visitor->visitNode($this, $level, $layoutElements);
	}

	public function __toString() {
		return "NODE " . $this->label;
	}
}

class Leaf extends GraphComponent {
	
	public function __construct($label) {
		parent::__construct($label);
	}

	function accept(GraphVisitor $visitor, $level = 1) {
		return $visitor->visitLeaf($this, $level);
	}
	
	public function __toString() {
		return "LEAFSR " . $this->label;
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
}
?>