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
		
		// automatical line feed from dot program
		if (!(strpos($this->actualLine, "[") === false) && (strpos($this->actualLine, "]") === false)) {
			$line = substr($this->actualLine, 0, strlen($this->actualLine) - 2);
			
			// retrieve next line
			$nextLine = fgets($this->parseFileHandle);
			$this->actualLine = $line . $nextLine;
		} 
		
		return $this->actualLine;
	}
	
	private function parseAdotFile($adotFile) {
		
		$this->parseFileHandle = fopen($adotFile, "r");
		
		// ommit first line: digraph G {
		$this->getNewLine();
		// ommit second line: node [label="\N"];
		$this->getNewLine();

		$graph = $this->parseGraph();

		fclose($this->parseFileHandle);

		return $graph;
	}

	private function parseGraph() {
		$subgraph = array();
		
		// retrieve boundbox rectangle: graph [bb="0,0,62,108"]; --> 0,0,62,108
		$this->getNewLine();
		$bb = $this->retrieveBoundingBox();
		
		$line = $this->getNewLine();
		while (!(strpos($line, "subgraph") === false) ||
			   !(strpos($line, "{") === false)) {
			array_push($subgraph, $this->parseGraph());
			
			$line = $this->getNewLine();
		}
		
		$nodes = $this->retrieveNodes();
		
		$edges = $this->retrieveEdges($file_handle);
		
		return array('bb'=>$bb, 'nodes'=>$nodes, 'edges'=>$edges, 'subgraph'=>$subgraph);
	}
	
	private function retrieveBoundingBox() {
		$bb = $this->retrieveParam($this->actualLine, 'bb');
		return explode(",", $bb);
	}
	
	private function retrieveEdges() {
		$edges = array();

		$line = $this->actualLine;
		
		while (!$this->isEnd($line)) {
			$edge = array();
			$edge['pos'] = $this->retrieveParam($line, 'pos');
			
			$edge['pos'] = explode(" ", $edge['pos']);
			
			foreach ($edge['pos'] as $key => $value) {
				$edge['pos'][$key] = explode(",", $value);
			}
			
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
			$node['pos'] = explode(",", $node['pos']);
			
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