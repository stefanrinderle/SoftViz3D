<?php

class LayerX3dCalculator extends CApplicationComponent {
	public function calculate($root) {
		$this->addTranslationToLayer($root, 0, 0);
	}
	
	private function addTranslationToLayer($node, $transX, $transZ) {
		$x3dInfos = $node->getX3dInfos();
		
		$nodeWidth = $x3dInfos->bb['size']['width'];
		$nodeLength = $x3dInfos->bb['size']['length'];
		
		if ($node->level != 0) {
			// get translation of parent
			$translation = array();
			$translation['x'] = $transX - $nodeWidth / 2;
			$translation['y'] = $x3dInfos->bb['position']['y'];
			$translation['z'] = $transZ - $nodeLength / 2;
			
			// !!! SAVE X3DINFOS !!!
			$x3dInfos->bb['translation'] = $translation;
			$node->setX3dInfos($x3dInfos);
		}
		
		// calculate values for the children nodes
		$content = InputNode::model()->findAllByAttributes(array('parent_id'=>$node->id));
		
		foreach ($content as $key => $value) {
			$label = trim($value->name);
		
			// layout node position
			$nodePositionX = $x3dInfos->nodes[$label]['position']['x'];
			$nodePositionZ = $x3dInfos->nodes[$label]['position']['z'];
			if ($node->level != 0) {
				$nodePositionX = $nodePositionX + ($transX - ($nodeWidth / 2));
				$nodePositionZ = $nodePositionZ + ($transZ - ($nodeLength / 2));
			}
		
			$this->addTranslationToLayer($value, $nodePositionX, $nodePositionZ);
		}
	}
}