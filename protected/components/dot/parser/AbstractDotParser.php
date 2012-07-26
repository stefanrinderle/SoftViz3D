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
		$result["attributes"] = array();
		
		$this->getNewLine();
		
		while (!$this->isEnd()) {
			//check for key words graph and node
			$hasMatch = preg_match($this->idPattern, $this->currentLine, $idMatch);
			if ($hasMatch) {
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
				
				if (array_key_exists('bb', $result["attributes"])) {
					$result["attributes"]["bb"] = explode(",", $result["attributes"]["bb"]);
				}
				
			} else if ($isSubgraphLine) {
				array_push($result["content"], $this->parseGraph());
			} else if ($isEdgeLine) {
				$edge = $this->parseEdgeLine();
				
				if (array_key_exists('pos', $edge["attributes"])) {
					$test = explode(" ", $edge["attributes"]["pos"]);
					
					$newPosition = array();
					foreach ($test as $key => $value) {
						$temp = explode(",", $value);
						if ($key == 0) {
							// omit "e"
							array_push($newPosition, array('x' => $temp[1], 'y' => 0, 'z' => $temp[2]));
						} else {
							array_push($newPosition, array('x' => $temp[0], 'y' => 0, 'z' => $temp[1]));
						}
					}
					
					$edge["attributes"]["pos"] = $newPosition;
				}
				
				if (array_key_exists('style', $edge["attributes"])) {
					$lineWidth = $edge['attributes']['style'];
					// TODO: regex
					$lineWidth = substr($lineWidth, strpos($lineWidth, "(") + 1, strlen($lineWidth) - strpos($lineWidth, "(") - 2);
						
					$edge["attributes"]["style"] = $lineWidth;
				}
				
				array_push($this->edgeStore, $edge);
			} else if ($isNodeLine) {
				$tmpNode = $this->parseNodeLine();
				
				if (array_key_exists('pos', $tmpNode["attributes"])) {
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
		$result["attributes"] = $this->getAttributes($this->currentLine);
		
		return $result;
	}
	
	private function getAttributes() {
		$newAttrArray = array();
		
		// get attribute string
		$hasAttributes = preg_match($this->attrPattern, $this->currentLine, $attrMatch);
		
		if ($hasAttributes) {
			//http://stackoverflow.com/questions/168171/regular-expression-for-parsing-name-value-pairs
			$splitPattern = '/((?:"[^"]*"|[^=,])*)=((?:"[^"]*"|[^=,])*)/';
			preg_match_all($splitPattern, $attrMatch[1], $attrGroupMatch);
			
			for ($i = 0; $i < count($attrGroupMatch[1]); $i++) {
				//remove " and spaces
				$value = str_replace('"', "", $attrGroupMatch[2][$i]);
				$value = trim($value);
					
				$key = trim($attrGroupMatch[1][$i]);
					
				$newAttrArray[$key] = $value;
			}
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