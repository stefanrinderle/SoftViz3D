<?php

abstract class GraphVisitor {
	abstract function visitNode(Node $comp, $level);
	abstract function visitLeaf(Leaf $comp, $level);
}

class SimpleSearchVisitor extends GraphVisitor {
	private $term;
	private $matches = array();
	
	function __construct($term) {
		$this->term = preg_quote($term);
	}

	function testText($text) {
		return preg_match("/$this->term/", $text);
	}

	function visitNode(Node $comp, $level) {
		if ($this->testText($comp->label)) {
			$this->matches[] = $comp;
		}
	}

	function visitLeaf(leaf $comp, $level) {
		if ($this->testText($comp->label)) {
			$this->matches[] = $comp;
		}
	}

	function matches() {
		return $this->matches;
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
// 	public $isMain;
	// 	public $depth;

	public $flatEdges;

	public function __construct($label) {
		parent::__construct($label);
		
		$this->content = array();
		$this->flatEdges = array();
	}
	
	function accept(GraphVisitor $visitor, $level = 1) {
		$visitor->visitNode($this, $level);
		foreach ($this->content as $child) {
			$child->accept($visitor, $level + 1);
		}
		//TODO add edges foreach
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
		$visitor->visitLeaf($this, $level);
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