<?php

/**
 * Parse an dot file given as a file path to a graph structure.
 */
class DotParser extends AdotParser
{
	
	private $parseFileHandle;
	private $connection;
	
	public function parse($dotFile)
	{
		$this->connection = Yii::app()->db;
		
		$this->parseFileHandle = fopen($dotFile, "r");
		
		// ommit first line: digraph G {
		$this->getNewLine();

		$this->parseGraph("MAIN_NODE");

		fclose($this->parseFileHandle);
	}
	
	protected function parseGraph($label, $parent = 0, $level = 0) {
		$currentId = TreeElement::createAndSaveTreeElement($label, $parent, $level);
		
		$line = $this->getNewLine();
		while (!(strpos($line, "subgraph") === false) || !(strpos($line, "{") === false)) {
			//subgraph node_3 {
			$label = substr($line, strpos($line, "subgraph") + strlen("subgraph"), strpos($line, "{") - strlen("subgraph") - 2);
			
			$this->parseGraph($label, $currentId, $level + 1);
	
			$line = $this->getNewLine();
		}
	
		$this->retrieveLeafs($currentId, $level + 1);
		
		$this->retrieveEdges();
	}
	
	protected function retrieveEdges() {
		$line = $this->actualLine;
	
		while (!$this->isEnd($line)) {
			$label = $this->retrieveName($line);
			//" name1 -> name 2"
			$in = trim(substr($line, 0, strpos($line, "->") - 1)); 
			$out = trim(substr($line, strpos($line, "->") + 2, strpos($line, ";") - (strpos($line, "->") + 2)));
			
			//TODO: What happens if a node wasnt declared before?
			//TODO: find selects only one node. This is ok as long as every label is unique
			$out_id = TreeElement::model()->find('label=:label', array(':label'=>$out))->id;
			$in_id = TreeElement::model()->find('label=:label', array(':label'=>$in))->id;
			EdgeElement::createAndSaveEdgeElement($label, $out_id, $in_id);
			
	
			$this->getNewLine();
			$line = $this->actualLine;
		}
	}
	
	protected function retrieveLeafs($parent, $level) {
		$line = $this->actualLine;
	
		while (!($this->isEdge($line) || $this->isEnd($line))) {
			$currentId = TreeElement::createAndSaveTreeElement($this->retrieveName($line), $parent, $level);
			
			$this->getNewLine();
			$line = $this->actualLine;
		}
	}
	
	protected function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
		
		$this->checkLineFeed();
		
		return $this->actualLine;
	}
	
}