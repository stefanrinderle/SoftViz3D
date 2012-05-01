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

		$graph = $this->parseGraph("MAIN NODE");

		fclose($this->parseFileHandle);

		return $graph;
	}
	
	protected function parseGraph($label) {
 		$current = new Node($label);
		
		$line = $this->getNewLine();
		while (!(strpos($line, "subgraph") === false) || !(strpos($line, "{") === false)) {
			//subgraph node_3 {
			$label = substr($line, strpos($line, "subgraph") + strlen("subgraph"), strpos($line, "{") - strlen("subgraph") - 2);
			
			array_push($current->content, $this->parseGraph($label));
	
			$line = $this->getNewLine();
		}
	
		$nodes = $this->retrieveLeafs();
		$current->content = array_merge($current->content, $nodes);
		
		$edges = $this->retrieveEdges();
		$current->flatEdges = array_merge($current->flatEdges, $edges);
	
		return $current;
	}
	
	protected function retrieveEdges() {
		$edges = array();
	
		$line = $this->actualLine;
	
		while (!$this->isEnd($line)) {
			$label = $this->retrieveName($line);
			//" name1 -> name 2"
			$out = trim(substr($dotEdge, 0, strpos($line, "->") - 2)); 
			$in = trim(substr($dotEdge, strpos($line, "->") + 1));
			
			$edge = new Edge($label, $in, $out);
			
			array_push($edges, $edge);
	
			$this->getNewLine();
			$line = $this->actualLine;
		}
	
		return $edges;
	}
	
	protected function retrieveLeafs() {
		$leafs = array();
	
		$line = $this->actualLine;
	
		while (!($this->isEdge($line) || $this->isEnd($line))) {
			$leaf = new Leaf($this->retrieveName($line));
			
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