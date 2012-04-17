<?php

class DotParser extends CApplicationComponent
{
	
	private $parseFileHandle;
	private $actualLine;
	
	public function parse($adotFile)
	{
		$graph = $this->parseAdotFile($adotFile);
		
		return $graph;
	}
	
	private function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
	}
	
	private function parseAdotFile($adotFile) {
		
		$this->parseFileHandle = fopen($adotFile, "r");
		
		// ommit first line: digraph G {
		$this->getNewLine();
		// ommit second line: node [label="\N"];
		$this->getNewLine();

		// retrieve boundbox rectangle: graph [bb="0,0,62,108"]; --> 0,0,62,108
		$this->getNewLine();
		$bb = $this->retrieveParam($this->actualLine, 'bb');
		
		$this->getNewLine();
		$nodes = $this->retrieveNodes();
		
		$edges = $this->retrieveEdges($file_handle);

		fclose($this->parseFileHandle);

		$graph = array('bb'=>$bb, 'nodes'=>$nodes, 'edges'=>$edges);
		
		return $graph;
	}

	private function retrieveEdges() {
		$edges = array();

		$line = $this->actualLine;
		
		while (!$this->isEnd($line)) {
			$edge = array();
			$edge['pos'] = $this->retrieveParam($line, 'pos');
			
			$edges[$this->retrieveName($line)] = $edge;
			
			$this->getNewLine();
			$line = $this->actualLine;
		}
		
		return $edges;
	}

	private function retrieveNodes() {
		$nodes = array();

		$line = $this->actualLine;
		
		while (!($this->isEdge($line) || $this->isEnd($line))) {
			$node = array();
			$node['pos'] = $this->retrieveParam($line, 'pos');
			
			$nodes[$this->retrieveName($line)] = $node; 
			
			$this->getNewLine();
			$line = $this->actualLine;
		}
		
		return $nodes;
	}

	private function isEdge($line) {
		return (!(strpos($line, "->") === false));
	}
	
	private function isEnd($line) {
		if ($this->actualLine) {
			return (!(strpos($line, "}") === false));
		} else {
			return false;
		}
	}

	private function retrieveName($line) {
		$startParamsPos = strpos($line, "[");
		
		if ($startParamsPos === false) {
			return trim($line);
		} else {
			return trim(substr($line, 0, $startParamsPos));
		}
	}
	
	private function retrieveParam($line, $param) {
		$result = "";
		// get the beginning of the param
		$start = strpos($line, $param);
		// ommit = and "
		$result = substr($line, $start + strlen($param) + 2);

		$end = strpos($result, '"');
		$result = substr($result, 0, $end);

		return $result;
	}
}