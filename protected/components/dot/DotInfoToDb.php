<?php

class DotInfoToDb extends CApplicationComponent {
	
	public function writeToDb($dotArray)
	{
		$this->parseGraph($dotArray);
	}
	
	protected function parseGraph($graph, $parent = 0, $level = 0) {
		$currentId = TreeElement::createAndSaveTreeElement($graph[label], $parent, $level);
		
		$edges = array();
		foreach ($graph[content] as $value) {
			if ($value[type] == "edge") {
				array_push($edges, $value);
			} else if ($value[type] == "node") {
				$this->retrieveNode($value, $currentId, $level);
			} else if ($value[type] == "sub") {
				$this->parseGraph($value, $currentId, $level + 1);
			}
		}
		
		foreach ($edges as $value) {
			$this->retrieveEdge($value);
		}
	}
	
	protected function retrieveEdge($edge) {
		$label = $edge[label];
		$out = $edge[node1];
		$in = $edge[node2];
			
		$out_id = TreeElement::model()->find('label=:label', array(':label'=>$out))->id;
		$in_id = TreeElement::model()->find('label=:label', array(':label'=>$in))->id;
		EdgeElement::createAndSaveEdgeElement($label, $out_id, $in_id);
	}
	
	protected function retrieveNode($value, $parent, $level) {
		TreeElement::createAndSaveLeafTreeElement($value[label], $parent, $level);
	}
}