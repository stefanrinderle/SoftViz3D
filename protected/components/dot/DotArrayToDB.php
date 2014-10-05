<?php

class DotArrayToDB extends CApplicationComponent {
	private $rootId;
	
	public function save($projectId, $dotArray) {
		$this->saveHierarchy($projectId, $dotArray);
		
		$this->saveDependencies($projectId, $dotArray['edges']);

		return $this->rootId;
	}
	
	private function saveHierarchy($projectId, $element, $parentId = null, $level = 0) {
		$identifier = $element["id"];
		$label = $this->getLabel($element);
		
		if (array_key_exists('content', $element)) {
			$id = InputNode::createAndSave($projectId, $identifier, $label, $parentId, $level);
			
			foreach ($element["content"] as $value) {
				$this->saveHierarchy($projectId, $value, $id, $level + 1);
			}
			
			if (is_null($parentId)) {
				$this->rootId = $id;
			}
		} else {
			//TODO: das geht besser - sieht ja scheiße aus
			if (array_key_exists('metric1', $element["attributes"])) {
				$metric1 = $element["attributes"]["metric1"];
			} else {
				$metric1 = null;
			}
			if (array_key_exists('metric2', $element["attributes"])) {
				$metric2 = $element["attributes"]["metric2"];
			} else {
				$metric2 = null;
			}
			
			InputLeaf::createAndSave($projectId, InputTreeElement::$TYPE_LEAF, $identifier, $label, $parentId, $level, $metric1, $metric2);
		} 
	}
	
	private function getLabel($element) {
		if (array_key_exists('label', $element["attributes"])) {
			$label = $element["attributes"]["label"];
		} else {
			$label = $element["id"];
		}
		
		return $label;
	}
	
	private function saveDependencies($projectId, $edges) {
		$treeElements = InputTreeElement::model()->findAllByAttributes(
							array('projectId' => $projectId), array('select'=>'id, name, parentId'));
	
		$treeArray = array();
		foreach ($treeElements as $element) {
			$treeArray[$element->name] = array('id' => $element->id, 'parentId' => $element->parentId);
		}
	
		// edges 
		$edgesToSave = array();
		
		foreach ($edges as $edge) {
			$out = $treeArray[$edge["source"]];
			$in = $treeArray[$edge["destination"]];
	
			array_push($edgesToSave, InputDependency::createAndSaveDotInputDependency(
					$projectId, $edge["id"], $out["id"], $in["id"], $out["parentId"], $in["parentId"]));
		}
	
		return $edgesToSave;
	}
	
}