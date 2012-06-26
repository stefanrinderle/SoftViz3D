<?php

class DotInfoToDb extends CApplicationComponent {
	
	public function writeToDb($dotArray) {
		/* reset database */
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
		$this->parseGraph($dotArray);
	}
	
	protected function parseGraph($graph, $parent = 0, $level = 0) {
		// check for nodes in graph layer
		$import = true;
// 		foreach ($graph[content] as $value) {
// 			if ($value[type] == "node" && $value[label] != "graph" && $value[label] != "node"){
// 				$import = true;
// 				break;
// 			}
// 		}
		
		if ($import) {
			$currentId = LayerElement::createAndSave($graph[label], $parent, $level);
		} else {
			$currentId = $parent;
			$level = $level - 1;
		}
		
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
		if ($value[label] != "graph" && $value[label] != "node") {
			
			$metric1 = $value[attr][metric1];
			$metric2 = $value[attr][metric2];

			// TODO add additional attributes for the input
			
			LeafElement::createAndSave($value[label], $parent, $level + 1, $metric1, $metric2);
		}
	}
}