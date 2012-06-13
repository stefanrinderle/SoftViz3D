<?php

class DotInfoToDb extends CApplicationComponent {
	
	public function writeToDb($dotArray)
	{
		/* reset database */
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
		$this->parseGraph($dotArray);
	}
	
	protected function parseGraph($graph, $parent = 0, $level = 0) {
		$currentId = LayerElement::createAndSave($graph[label], $parent, $level);
		
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
		$in = $edge[node1];
		$out = $edge[node2];
			
		$out_id = TreeElement::model()->find('label=:label', array(':label'=>$out))->id;
		$in_id = TreeElement::model()->find('label=:label', array(':label'=>$in))->id;
		EdgeElement::createAndSaveEdgeElement($label, $out_id, $in_id);
	}
	
	protected function retrieveNode($value, $parent, $level) {
		if ($value[label] != "graph" && $value[label] != "node") {
			$width = 1;
			$height = 1;

			if ($value[attr][width]) {
				$width = $value[attr][width]; 
			}
			if ($value[attr][height]) {
				$height = $value[attr][height];
			}
			
			LeafElement::createAndSave($value[label], $parent, $level + 1, $width, $height);
		}
	}
}