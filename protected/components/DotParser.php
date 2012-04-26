<?php

abstract class DotParser extends CApplicationComponent
{
	protected $actualLine;
	
	abstract function parse($something);
	
	abstract protected function getNewLine();
	
	protected function parseGraph() {
		$subgraph = array();
	
		// retrieve boundbox rectangle: graph [bb="0,0,62,108"]; --> 0,0,62,108
		$this->getNewLine();
		$bb = $this->retrieveBoundingBox();
	
		$line = $this->getNewLine();
		while (!(strpos($line, "subgraph") === false) || !(strpos($line, "{") === false)) {
			array_push($subgraph, $this->parseGraph());
	
			$line = $this->getNewLine();
		}
	
		$nodes = $this->retrieveNodes();
	
		$edges = $this->retrieveEdges($file_handle);
	
		return array('bb'=>$bb, 'nodes'=>$nodes, 'edges'=>$edges, 'subgraph'=>$subgraph);
	}
	
	protected function retrieveBoundingBox() {
		$bb = $this->retrieveParam($this->actualLine, 'bb');
		return explode(",", $bb);
	}
	
	protected function retrieveEdges() {
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
	
	protected function retrieveNodes() {
		$nodes = array();
	
		$line = $this->actualLine;
	
		while (!($this->isEdge($line) || $this->isEnd($line))) {
			$node = array();
			$node['pos'] = $this->retrieveParam($line, 'pos');
			$node['pos'] = explode(",", $node['pos']);
	
			$node['size']['width'] = $this->retrieveParam($line, 'width');
			$node['size']['height'] = $this->retrieveParam($line, 'height');
	
			$node['type'] = $this->retrieveParam($line, 'type');
	
			$nodes[$this->retrieveName($line)] = $node;
	
			$this->getNewLine();
			$line = $this->actualLine;
		}
	
		return $nodes;
	}
	
	protected function isEdge($line) {
		return (!(strpos($line, "->") === false));
	}
	
	protected function isEnd($line) {
		if ($this->actualLine) {
			return (!(strpos($line, "}") === false));
		} else {
			return false;
		}
	}
	
	protected function retrieveName($line) {
		$startParamsPos = strpos($line, "[");
	
		if ($startParamsPos === false) {
			return trim($line);
		} else {
			return trim(substr($line, 0, $startParamsPos));
		}
	}
	
	protected function retrieveParam($line, $param) {
		$result = "";
	
		$params = substr($line, strpos($line, "["));
	
		// get the beginning of the param
		$start = strpos($params, $param);
		// ommit = and "
		if (substr($params, $start + strlen($param) + 1, 1) == '"') {
			$result = substr($params, $start + strlen($param) + 2);
			$end = strpos($result, '"');
			$result = substr($result, 0, $end);
		} else {
			$result = substr($params, $start + strlen($param) + 1);
			$end = strpos($result, ',');
			$result = substr($result, 0, $end);
		}
	
		return $result;
	}
}