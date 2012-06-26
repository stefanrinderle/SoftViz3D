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
	
	private function createEdges() {
		$attr = array(
				'select'=>'id, label, parent_id',
		);
		$treeElements = TreeElement::model()->findAll($attr);
		
		$treeArray = array();
		foreach ($treeElements as $element) {
			$treeArray[$element->label] = array(id => $element->id, parent_id => $element->parent_id); 
		}
		
		$edgesToSave = array();
		
		foreach ($this->edgeStore as $edge) {
			$out = $treeArray[$edge[out]];
			$in = $treeArray[$edge[in]];
			
			array_push($edgesToSave, EdgeElement::createDotEdgeElement($edge[label], $out[id], $in[id], $out[parent_id], $in[parent_id]));
		}
		
		return $edgesToSave;
	}
	
	protected function parseGraph($label = "G", $parent = 0, $level = 0) {
		$currentLayer = LayerElement::create($label, $parent, $level);
		
		$line = $this->getNewLine();
		
		$counter = 0;
		while (!$this->isEnd($line)) {
			$subgraphLine = !(strpos($line, "subgraph") === false) || !(strpos($line, "{") === false);
			$edgeLine = $this->isEdge($line);
			
			if ($subgraphLine) {
				$counter++;
				
				//subgraph node_3 {
				$label = substr($line, strpos($line, "subgraph") + strlen("subgraph") + 1, strpos($line, "{") - strlen("subgraph") - strpos($line, "subgraph") - 2);
				
				$this->parseGraph($label, $currentLayer->id, $level + 1);
			} else if($edgeLine) {
				$this->retrieveEdge();
			} else {
				if ($this->retrieveNode($parent, $level)) {
					$counter++;
				}
			}
			
			$line = $this->getNewLine();
		}
		
		//prevent import of empty layers
// 		if ($counter < 2) {
// 			$currentLayer->delete();
// 		} else {
			$this->rootId = $currentLayer->id;
// 		}
	}
	
	protected function retrieveNode($parent, $level) {
		$line = $this->actualLine;

		$label = $this->retrieveName($line);
			
		if ($label != "graph" && $label != "node") {
			$metric1 = $this->retrieveParam($line, 'metric1');
			$metric2 = $this->retrieveParam($line, 'metric2');

			LeafElement::createAndSave($label, $parent, $level, $metric1, $metric2);
			
			return true;
		} else {
			return false;
		}
	}
	
	protected function retrieveEdge() {
		$line = $this->actualLine;
	
		$label = $this->retrieveName($line);
		//" name1 -> name 2"
		$out = trim(substr($line, 0, strpos($line, "->") - 1)); 
		$in = trim(substr($line, strpos($line, "->") + 2, strpos($line, ";") - (strpos($line, "->") + 2)));
		
		$edge = array(label => $label, out => $out, in => $in);
		
		array_push($this->edgeStore, $edge);
	}
	
	protected function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
		
		$this->checkLineFeed();
		
		return $this->actualLine;
	}
	
}