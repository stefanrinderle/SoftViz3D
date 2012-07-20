<?php

class BestDotParser extends CApplicationComponent {
	public static $TYPE_NODE = "node";
	public static $TYPE_LEAF = "leaf";
	public static $EDGE_STORE = "edges";
	
	private $currentLine;
	
	private $parseFileHandle;
	
	private $edgeStore = array();
	
	private $attrPattern = '/.*\[(.*)\].*/';
	private $idPattern = '/"?([a-zA-Z0-9_\-\.]+).*(\[).*/';
	
	public function parse($dotFile, $includeEdges) {
		$this->parseFileHandle = fopen($dotFile, "r");
		
		$this->getNewLine();

		$result = $this->parseGraph();
		
		if ($includeEdges) {
			$result[BestDotParser::$EDGE_STORE] = $this->edgeStore;
		}
		
		fclose($this->parseFileHandle);
		
		return $result;
	}
	
	private function parseGraph() {
		$result = array();
		
		$result["id"] = $this->getGraphId($this->currentLine);
		$result["type"] = BestDotParser::$TYPE_NODE;
		$result["content"] = array();
		
		$this->getNewLine();
		
		while (!$this->isEnd($this->currentLine)) {
			//check for key words graph and node
			preg_match($this->idPattern, $this->currentLine, $idMatch);
			$isGraphAttributeLine = ($idMatch[1] == "graph" || $idMatch[1] == "node");
			
			$isSubgraphLine = preg_match('/subgraph\ .*\{/', $this->currentLine);
			$isEdgeLine = preg_match('/.*->.*/', $this->currentLine);
			$isNodeLine = preg_match('/.*;/', $this->currentLine);
			$isEmptyLine = (trim($this->currentLine) == "");
			
			if ($isGraphAttributeLine) {
				$result["attributes"] = $this->getAttributes();
			} else if ($isSubgraphLine) {
				array_push($result["content"], $this->parseGraph());
			} else if ($isEdgeLine) {
				array_push($this->edgeStore, $this->parseEdgeLine());
			} else if ($isNodeLine) {
				array_push($result["content"], $this->parseNodeLine());
			} else if ($isEmptyLine) {
				// do nothing
			}
			
			$this->getNewLine();
		}
		
		return $result;
	}
	
	private function getGraphId() {
		//digraph _Users_stefan_Sites_3dArch_x3d {
		$graphIdPattern = '/(digraph|graph|subgraph)(.+)\{/i';
	
		// && trim($treffer[2]) != ""
		if (preg_match($graphIdPattern, $this->currentLine, $treffer)) {
			if (trim($treffer[2]) != "") {
				$result = trim($treffer[2]);
			} else {
				throw new Exception("graph identifier empty" . $this->currentLine);
			}
		} else {
			throw new Exception("no graph identifier: " . $this->currentLine);
		}
	
		return $result;
	}
	
	/**
	 * Node identifier is alphanumeric or ., _, -
	 * Node identifier can have "..." around
	 */
	private function parseNodeLine() {
		$result = array();
		
		// attributes
		$hasAttributes = preg_match($this->attrPattern, $this->currentLine, $attrMatch);
		
		// get node identifier
		if ($hasAttributes) {
			$idPattern = '/"?([a-zA-Z0-9_\-\.]+).*(\[).*/';
		} else {
			$idPattern = '/"?([a-zA-Z0-9_\-\.]+).*(;)/';
		}
		preg_match($idPattern, $this->currentLine, $idMatch);
		$result["id"] = $idMatch[1];
		$result["type"] = BestDotParser::$TYPE_LEAF;
		
		// retrieve attributes
		if ($hasAttributes) {
			$result["attributes"] = $this->getAttributes($this->currentLine);
		} else {
			$result["attributes"] = array();
		}
		
		return $result;
	}
	
	private function getAttributes() {
		$hasAttributes = preg_match($this->attrPattern, $this->currentLine, $attrMatch);
		
		$attrArray = explode(",", $attrMatch[1]);
			
		$attributes = array();
		foreach($attrArray as $attrString) {
			$attr = explode("=", $attrString);
			$attributes[$attr[0]] = $attr[1];
		}
			
		return $attributes;
	}
	
	private function parseEdgeLine() {
		$result = array();
		
		$edgePattern = '/\ *"?([a-zA-Z0-9_\-\.]+)"?\ *->\ *"?([a-zA-Z0-9_\-\.]+)"?.*;/';
		preg_match($edgePattern, $this->currentLine, $attrMatch);
		
		$result["id"] = $attrMatch[1] . " -> " . $attrMatch[2];
		$result["source"] = $attrMatch[1];
		$result["destination"] = $attrMatch[2];
		$result["attributes"] = $this->getAttributes($this->currentLine);
		
		return $result;
	}
	
	private function getNewLine() {
		$this->currentLine = fgets($this->parseFileHandle);
		
		//TODO: check this also for normal dot files...
		//$this->checkLineFeed();
	}
	
	private function checkLineFeed() {
		// automatical line feed from dot program
		if (!(strpos($this->currentLine, "[") === false) && (strpos($this->currentLine, "]") === false)) {
			$line = substr($this->currentLine, 0, strlen($this->currentLine) - 2);
	
			// retrieve next line
			$nextLine = fgets($this->parseFileHandle);
			$this->currentLine = $line . $nextLine;
		}
	}
	
	private function isEnd($line) {
		if ($this->currentLine) {
			return (!(strpos($line, "}") === false));
		} else {
			return false;
		}
	}
	
}