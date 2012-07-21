<?php

abstract class AbstractDotParser extends CApplicationComponent {
	
	protected $currentLine;
	protected $result;
	
	private $edgeStore = array();
	private $attrPattern = '/.*\[(.*)\].*/';
	private $idPattern = '/"?([a-zA-Z0-9_\-\.]+).*(\[).*/';
	
	public abstract function parse($data, $includeEdges = true);
	
	protected abstract function getNewLine();
	
	protected function start($includeEdges) {
		$this->edgeStore = array();
		
		$this->getNewLine();
		
		$this->result = $this->parseGraph();
		
		if ($includeEdges) {
			$this->result['edges'] = $this->edgeStore;
		}
	}
	
	private function parseGraph() {
		$result = array();
		
		$result["id"] = $this->getGraphId();
		$result["content"] = array();
		
		$this->getNewLine();
		
		while (!$this->isEnd()) {
			//check for key words graph and node
			preg_match($this->idPattern, $this->currentLine, $idMatch);
			if ($idMatch[1]) {
				$isGraphAttributeLine = ($idMatch[1] == "graph" || $idMatch[1] == "node");
			} else {
				$isGraphAttributeLine = false;
			}
			
			$isSubgraphLine = preg_match('/subgraph\ .*\{/', $this->currentLine);
			$isEdgeLine = preg_match('/.*->.*/', $this->currentLine);
			$isNodeLine = preg_match('/.*;/', $this->currentLine);
			$isEmptyLine = (trim($this->currentLine) == "");
			
			if ($isGraphAttributeLine) {
				$result["attributes"] = $this->getAttributes();
				
				if ($result["attributes"]["bb"]) {
					$result["attributes"]["bb"] = explode(",", $result["attributes"]["bb"]);
				}
				
			} else if ($isSubgraphLine) {
				array_push($result["content"], $this->parseGraph());
			} else if ($isEdgeLine) {
				$tmpEdge = $this->parseEdgeLine();
				
				if ($tmpEdge["attributes"]["pos"]) {
					$test = explode(" ", $tmpEdge["attributes"]["pos"]);
					
					$newPosition = array();
					foreach ($test as $key => $value) {
						$temp = explode(",", $value);
						if ($key == 0) {
							// omit "e"
							array_push($newPosition, array('x' => $temp[1], 'z' => $temp[2]));
						} else {
							array_push($newPosition, array('x' => $temp[0], 'z' => $temp[1]));
						}
					}
					
					$tmpEdge["attributes"]["pos"] = $newPosition;
				}
				
				array_push($this->edgeStore, $tmpEdge);
			} else if ($isNodeLine) {
				$tmpNode = $this->parseNodeLine();
				
				if ($tmpNode["attributes"]["pos"]) {
					$tmpNode["attributes"]["pos"] = explode(",", $tmpNode["attributes"]["pos"]);
				}
				
				array_push($result["content"], $tmpNode);
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
		
		// retrieve attributes
		if ($hasAttributes) {
			$result["attributes"] = $this->getAttributes($this->currentLine);
		} else {
			$result["attributes"] = array();
		}
		
		return $result;
	}
	
	private function getAttributes() {
		// get attribute string
		$hasAttributes = preg_match($this->attrPattern, $this->currentLine, $attrMatch);
		
		//http://stackoverflow.com/questions/168171/regular-expression-for-parsing-name-value-pairs
		$splitPattern = '/((?:"[^"]*"|[^=,])*)=((?:"[^"]*"|[^=,])*)/';
		preg_match_all($splitPattern, $attrMatch[1], $attrGroupMatch);
		
		$newAttrArray = array();
		for ($i = 0; $i < count($attrGroupMatch[1]); $i++) {
			//remove " and spaces
			$value = str_replace('"', "", $attrGroupMatch[2][$i]);
			$value = trim($value);
			
			$key = trim($attrGroupMatch[1][$i]);
			
			$newAttrArray[$key] = $value;
		}
		
		return $newAttrArray;
	}
	
	private function parseEdgeLine() {
		$line = $this->currentLine;
		
		$result = array();
		
		$edgePattern = '/\ *"?([a-zA-Z0-9_\-\.]+)"?\ *->\ *"?([a-zA-Z0-9_\-\.]+)"?.*;/';
		preg_match($edgePattern, $this->currentLine, $attrMatch);
		
		$result["id"] = $attrMatch[1] . " -> " . $attrMatch[2];
		$result["source"] = $attrMatch[1];
		$result["destination"] = $attrMatch[2];
		$result["attributes"] = $this->getAttributes($line);
		
		return $result;
	}
	
	private function isEnd() {
		if ($this->currentLine) {
			return (!(strpos($this->currentLine, "}") === false));
		} else {
			return false;
		}
	}
	
	protected function checkLineFeed() {
		$line = trim($this->currentLine);
		$isNewLine = (substr($line, strlen($line) - 1, 1) == "\\");
	
		if ($isNewLine) {
			$nextLine = $this->getNewLine();
			$this->currentLine = substr($line, 0, strlen($line) - 1) . $this->currentLine;
		}
	}
	
}