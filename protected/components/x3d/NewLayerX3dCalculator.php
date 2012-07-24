<?php

class NewLayerX3dCalculator extends CApplicationComponent {
	
	public function calculate($rootId) {
		$this->addTranslationToLayer($rootId, 0, 0, 1);
	}
	
	private function addTranslationToLayer($rootId, $transX, $transZ, $isRoot = false) {
		$layoutElement = BoxElement::model()->findByAttributes(array('inputTreeElementId'=>$rootId));

		$sizeArray = explode(" ", $layoutElement->size);
		$translationArray = explode(" ", $layoutElement->translation);
		
		$nodeWidth = $sizeArray[0];
		$nodeLength = $sizeArray[1];
		
		$translation = array();
		
		if ($isRoot) {
			$translation['x'] = 0;
			$translation['y'] = $translationArray[1];
			$translation['z'] = 0;
		} else {
			$translation['x'] = $translationArray[0] + $transX;
			$translation['y'] = $translationArray[1];
			$translation['z'] = $translationArray[2] + $transZ;
		}
			
		$layoutElement->saveTranslation($translation);
		
		// calculate values for the children nodes
		
		// first find the chlidren elements of the input tree 
		$content = InputTreeElement::model()->findAllByAttributes(array('parent_id'=>$layoutElement->inputTreeElementId));
		
		foreach ($content as $key => $value) {
			if (!$value->isLeaf) {
				// find the according layout representations
				$element = BoxElement::model()->findByAttributes(array(
						'inputTreeElementId'=>$value->id,
						'type'=>BoxElement::$TYPE_FOOTPRINT));
			
				$size = explode(" ", $layoutElement->size);
				
				// layout node position
				$nodePosition = $element->getTranslation();
				$nodePosition[0] = $nodePosition[0] + $transX - $size[0] / 2;
				$nodePosition[2] = $nodePosition[2] + $transZ - $size[1] / 2;
				
				$this->addTranslationToLayer($value->id, $nodePosition[0], $nodePosition[2]);
			} else {
				$element = BoxElement::model()->findByAttributes(array(
						'inputTreeElementId'=>$value->id,
						'type'=>BoxElement::$TYPE_BUILDING));

				$size = explode(" ", $layoutElement->size);
				
				$elemTrans = $element->getTranslation();
				$elemTrans[0] = $elemTrans[0] + $transX - $size[0] / 2;
				$elemTrans[2] = $elemTrans[2] + $transZ - $size[1] / 2;
				$element->saveTranslation($elemTrans);
			}
		}
	}
}