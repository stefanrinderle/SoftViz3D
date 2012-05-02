<?php

Yii::import('application.extensions.*');
require_once('GraphObjects.php');

/**
 * Parse an dot file given as a file path to a graph structure.
 */
class DotParser extends AdotParser
{
	
	private $parseFileHandle;
	
	public function parse($dotFile)
	{
		$this->parseFileHandle = fopen($dotFile, "r");
		
		// ommit first line: digraph G {
		$this->getNewLine();

		$graph = $this->parseGraph("MAIN_NODE", "ROOT");

		fclose($this->parseFileHandle);

		return $graph;
	}
	
	protected function parseGraph($label, $parent) {
 		$current = new Layer($label, $parent);
		
		$line = $this->getNewLine();
		while (!(strpos($line, "subgraph") === false) || !(strpos($line, "{") === false)) {
			//subgraph node_3 {
			$label = substr($line, strpos($line, "subgraph") + strlen("subgraph"), strpos($line, "{") - strlen("subgraph") - 2);
			
			array_push($current->content, $this->parseGraph($label, $current));
	
			$line = $this->getNewLine();
		}
	
		$nodes = $this->retrieveLeafs($current);
		$current->content = array_merge($current->content, $nodes);
		
		$edges = $this->retrieveEdges();
		$current->edges = array_merge($current->edges, $edges);
	
		return $current;
	}
	
	protected function retrieveEdges() {
		$edges = array();
	
		$line = $this->actualLine;
	
		while (!$this->isEnd($line)) {
			$label = $this->retrieveName($line);
			//" name1 -> name 2"
			$out = trim(substr($line, 0, strpos($line, "->") - 1)); 
			$in = trim(substr($line, strpos($line, "->") + 2, strpos($line, ";") - (strpos($line, "->") + 2)));
			
			$edge = new Edge($label, $in, $out);
			
			array_push($edges, $edge);
	
			$this->getNewLine();
			$line = $this->actualLine;
		}
	
		return $edges;
	}
	
	protected function retrieveLeafs($parent) {
		$leafs = array();
	
		$line = $this->actualLine;
	
		while (!($this->isEdge($line) || $this->isEnd($line))) {
			$leaf = new Leaf($this->retrieveName($line), $parent);
			
			array_push($leafs, $leaf);
			
			$this->getNewLine();
			$line = $this->actualLine;
		}
	
		return $leafs;
	}
	
	protected function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
		
		$this->checkLineFeed();
		
		return $this->actualLine;
	}
	
}