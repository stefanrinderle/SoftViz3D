<?php

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
// 	public $isMain;
	// 	public $depth;

	public $flatEdges;

	public function __construct($label) {
		parent::__construct($label);
		
		$this->content = array();
		$this->flatEdges = array();
	}

	public function __toString() {
		return "NODE " . $this->label;
	}
}

class Leaf extends GraphComponent {
	public function __construct($label) {
		parent::__construct($label);
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