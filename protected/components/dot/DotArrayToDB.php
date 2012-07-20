<?php

class DotArrayToDB extends CApplicationComponent {
	private $rootId;
	
	public function save($dotArray) {

		$this->saveHierarchy($dotArray);
		
		$edges = $this->createEdges($dotArray[BestDotParser::$EDGE_STORE]);

		return array(edges => $edges, rootId => $this->rootId);
	}
	
	private function saveHierarchy($element, $parentId = null, $level = 0) {
		$identifier = $element["id"];
		$label = $this->getLabel($element);
		
		if ($element["type"] == BestDotParser::$TYPE_NODE) {
			$id = LayerElement::createAndSave($identifier, $label, $parentId, $level);
			
			foreach ($element["content"] as $value) {
				$this->saveHierarchy($value, $id, $level + 1);
			}
			
			if (is_null($parentId)) {
				$this->rootId = $id;
			}
		} else if ($element["type"] == BestDotParser::$TYPE_LEAF) {
			$metric1 = $element["attributes"]["metric1"];
			$metric2 = $element["attributes"]["metric2"];
			
			LeafElement::createAndSave($identifier, $label, $parentId, $level, $metric1, $metric2);
		} 
	}
	
	private function getLabel($element) {
		if ($element["attributes"]["label"]) {
			$label = $element["attributes"]["label"];
		} else {
			$label = $element["id"];
		}
		
		return $label;
	}
	
	private function createEdges($edges) {
		// get all tree elements out of db
		$attr = array(
				'select'=>'id, name, parent_id',
		);
		$treeElements = TreeElement::model()->findAll($attr);
	
		$treeArray = array();
		foreach ($treeElements as $element) {
			$treeArray[$element->name] = array(id => $element->id, parent_id => $element->parent_id);
		}
	
		// edges 
		$edgesToSave = array();
		
		foreach ($edges as $edge) {
			$out = $treeArray[$edge["source"]];
			$in = $treeArray[$edge["destination"]];
	
			array_push($edgesToSave, EdgeElement::createDotEdgeElement(
					$edge["id"], $out["id"], $in["id"], $out["parent_id"], $in["parent_id"]));
		}
	
		return $edgesToSave;
	}
	
}