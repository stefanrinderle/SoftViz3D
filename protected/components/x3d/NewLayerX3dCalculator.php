<?php

class NewLayerX3dCalculator extends CApplicationComponent {
	
	public function calculate($rootId) {
		$this->addTranslationToLayer($rootId, 0, 0, 1);
	}
	
	private function addTranslationToLayer($rootId, $transX, $transZ, $isRoot = false) {
		$level++;
		
		$layoutElement = BoxElement::model()->findByAttributes(array('inputTreeElementId'=>$rootId));

		$sizeArray = explode(" ", $layoutElement->size);
		$translationArray = explode(" ", $layoutElement->translation);
		
		$nodeWidth = $sizeArray[0];
		$nodeLength = $sizeArray[1];
		
		$translation = array();
		
		if ($isRoot) {
			$translation['x'] = $sizeArray[0] / 2;
			$translation['y'] = $translationArray[1];
			$translation['z'] = $sizeArray[1] / 2;
		} else {
			$translation['x'] = $translationArray[0] + $transX;
			$translation['y'] = $translationArray[1];
			$translation['z'] = $translationArray[2] + $transZ;
		}
			
		$layoutElement->saveTranslation($translation);
		
		// calculate values for the children nodes
		
		// first find the chlidren elements of the input tree 
		$content = InputNode::model()->findAllByAttributes(array('parent_id'=>$layoutElement->inputTreeElementId));
		
		foreach ($content as $key => $value) {
			// find the according layout representations
			$element = BoxElement::model()->findByAttributes(array(
							'inputTreeElementId'=>$value->id,
							'type'=>BoxElement::$TYPE_FOOTPRINT));
			
			// layout node position
			$nodePosition = explode(" ", $element->translation);
		
			$this->addTranslationToLayer($value->id, $nodePosition[0], $nodePosition[2]);
		}
	}
}