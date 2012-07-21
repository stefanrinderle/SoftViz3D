<?php

class BestDotParser extends CApplicationComponent {
	public static $TYPE_NODE = "node";
	public static $TYPE_LEAF = "leaf";
	public static $EDGE_STORE = "edges";
	
	private $parseTypeFile = "file";
	private $parseTypeArray = "array";
	private $parseType;
	
	private $currentLine;
	
	private $result;
	private $inputArrayCounter;
	private $inputArray;
	
	private $parseFileHandle;
	
	private $edgeStore = array();
	
	private $attrPattern = '/.*\[(.*)\].*/';
	private $idPattern = '/"?([a-zA-Z0-9_\-\.]+).*(\[).*/';
	
	public function parseFile($dotFile, $includeEdges = true) {
		$this->parseType = $this->parseTypeFile;
		$this->parseFileHandle = fopen($dotFile, "r");
		
		$this->parse($includeEdges);
		
		fclose($this->parseFileHandle);
		
		return $this->result;
	}
	
	public function parseFileArray($array, $includeEdges = true) {
		$this->parseType = $this->parseTypeArray;
		
		$this->inputArray = $array;
		$this->inputArrayCounter = 0;
		
		$this->parse($includeEdges);
		
		return $this->result;
	}
	
	private function parse($includeEdges) {
		$this->getNewLine();
		
		$this->result = $this->parseGraph();
		
		if ($includeEdges) {
			$this->result[BestDotParser::$EDGE_STORE] = $this->edgeStore;
		}
	}
	
	private function parseGraph() {
		$result = array();
		
		$result["id"] = $this->getGraphId($this->currentLine);
		$result["type"] = BestDotParser::$TYPE_NODE;
		$result["content"] = array();
		
		$this->getNewLine();
		
		while (!$this->isEnd()) {
			//check for key words graph and node
			preg_match($this->idPattern, $this->currentLine, $idMatch);
			if ($idMatch[1]) {
				$isGraphAttributeLine = ($idMatch[1] == "graph" || $idMatch[1] == "node");
				//print_r("HIER " . $idMatch[1] . "<br />");
			}
			
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
			//TODO
			print_r("exception: " . $this->currentLine . "<br />");
			$result = "bla";
			//throw new Exception("no graph identifier: " . $this->currentLine);
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
		//TODO: make testline working without empty sace in explode
		
		//$testline = 'label="blabla", mnetric=12, bb="12,13,14,15"';
		//print_r("testline: " . $testline);
		
		$hasAttributes = preg_match($this->attrPattern, $this->currentLine, $attrMatch);
		
		$attrArray = explode(", ", $attrMatch[1]);
			
		$attributes = array();
		foreach($attrArray as $attrString) {
			$attr = explode("=", $attrString);
			
			//remove "
			$value = str_replace('"', "", $attr[1]);
			$attributes[$attr[0]] = $value;
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
		if ($this->parseType == $this->parseTypeFile) {
			$this->currentLine = fgets($this->parseFileHandle);
		} else if ($this->parseType == $this->parseTypeArray) {
			$this->inputArrayCounter++;
			$this->currentLine = $this->inputArray[$this->inputArrayCounter];
		}
		
		
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
	
	private function isEnd() {
		if ($this->currentLine) {
			return (!(strpos($this->currentLine, "}") === false));
		} else {
			return false;
		}
	}
	
}