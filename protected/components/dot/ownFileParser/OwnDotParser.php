<?php

/**
 * Parse an dot file given as a file path to a graph structure.
 */
class OwnDotParser extends AdotParser {
	private $parseFileHandle;
	private $connection;
	
	private $edgeStore = array();
	
	private $rootId;
	
	public function parse($dotFile) {
		$this->connection = Yii::app()->db;
		
		$this->parseFileHandle = fopen($dotFile, "r");
		
		// ommit first line: digraph G {
		$this->getNewLine();

		$this->parseGraph();

		fclose($this->parseFileHandle);

		return array(edges => $this->createEdges(), rootId => $this->rootId);
	}
	
	private $import = false;
	
	protected function parseGraph($name = "G", $parent = 0, $level = 0) {
		$name = str_replace('"', '', $name);
		$currentLayer = LayerElement::create($name, $parent, $level);
		
		$line = $this->getNewLine();
		
		$counter = 0;
		while (!$this->isEnd($line)) {
			
			$subgraphLine = (strpos($line, "subgraph") > 0 && strpos($line, "{") > 0);
			$edgeLine = $this->isEdge($line);
			$isEmptyLine = (trim($line) == "");
			
			if ($isEmptyLine) {
				// do nothing and get next line
			} else if ($subgraphLine) {
				$counter++;
				
				//subgraph node_3 {
				$name = substr($line, strpos($line, "subgraph") + strlen("subgraph") + 1, strpos($line, "{") - strlen("subgraph") - strpos($line, "subgraph") - 2);
				
				$this->parseGraph($name, $currentLayer->id, $level + 1);
			} else if($edgeLine) {
				$this->retrieveEdge();
			} else {
				if ($this->retrieveNode($currentLayer->id, $level + 1)) {
					$counter++;
				}
			}
			
			$line = $this->getNewLine();
		}
		
		//print_r($currentLayer->name . " " . $counter .  "<br /><br />");
 		//prevent import of empty layers
 		if ($counter < 2 && !$this->import) {
 			//$this->rootId = $currentLayer->id;
 			print_r($currentLayer->id . "<br /><br />");
 			$currentLayer->delete();
 		} else {
 			$this->import = true;
			$this->rootId = $currentLayer->id;
 		}
	}
	
	protected function retrieveNode($parent, $level) {
		$line = $this->actualLine;

		$name = $this->retrieveName($line);
		
		if ($name != "graph" && $name != "node") {
			$metric1 = $this->retrieveParam($line, 'metric1');
			$metric2 = $this->retrieveParam($line, 'metric2');

			$name = str_replace('"', '', $name);
			LeafElement::createAndSave($name, $parent, $level, $metric1, $metric2);
			
			return true;
		} else {
			return false;
		}
	}
	
	protected function retrieveEdge() {
		$line = $this->actualLine;
	
		$name = $this->retrieveName($line);
		//" name1 -> name 2"
		$out = trim(substr($line, 0, strpos($line, "->") - 1)); 
		$in = trim(substr($line, strpos($line, "->") + 2, strpos($line, ";") - (strpos($line, "->") + 2)));
		
		$edge = array(name => $name, out => $out, in => $in);
		
		array_push($this->edgeStore, $edge);
	}
	
	private function createEdges() {
		$attr = array(
				'select'=>'id, name, parent_id',
		);
		$treeElements = TreeElement::model()->findAll($attr);
	
		$treeArray = array();
		foreach ($treeElements as $element) {
			$treeArray[$element->name] = array(id => $element->id, parent_id => $element->parent_id);
		}
	
		$edgesToSave = array();
	
		foreach ($this->edgeStore as $edge) {
			$out = $treeArray[$edge[out]];
			$in = $treeArray[$edge[in]];
	
			array_push($edgesToSave, EdgeElement::createDotEdgeElement($edge[name], $out[id], $in[id], $out[parent_id], $in[parent_id]));
		}
	
		return $edgesToSave;
	}
	
	protected function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
		
		$this->checkLineFeed();
		
		return $this->actualLine;
	}
	
}