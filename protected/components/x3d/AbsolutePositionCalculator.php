<?php

class AbsolutePositionCalculator extends CApplicationComponent {
	
	public function calculate($rootId) {
		$this->addTranslationToLayer($rootId, 0, 0, 1);
	}
	
	private function addTranslationToLayer($rootId, $transX, $transZ, $isRoot = false) {
		$layoutElement = BoxElement::model()->findByAttributes(array('inputTreeElementId'=>$rootId));

		$sizeArray = $layoutElement->getSize();
		
		$nodeWidth = $sizeArray[0];
		$nodeLength = $sizeArray[1];
		
		$translation = $layoutElement->getTranslation();
		$translation[0] = $translation[0] + $transX;
		$translation[2] = $translation[2] + $transZ;
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
			
				$nodePosition = $this->setNewPosition($element, $transX, $transZ, $layoutElement);
				
				$this->addTranslationToLayer($value->id, $nodePosition[0], $nodePosition[2]);
			} else {
				$element = BoxElement::model()->findByAttributes(array(
						'inputTreeElementId'=>$value->id,
						'type'=>BoxElement::$TYPE_BUILDING));

				// should be not the case after all
				if ($element) {
					$nodePosition = $this->setNewPosition($element, $transX, $transZ, $layoutElement);
				}
			}
		}
		
		$contentEdges = InputDependency::model()->findAllByAttributes(array('parent_id' => $layoutElement->inputTreeElementId));
		foreach ($contentEdges as $key => $value) {
			$element = EdgeElement::model()->findByAttributes(array(
						'inputDependencyId'=>$value->id));
			
			$nodePosition = $this->setNewPosition($element, $transX, $transZ, $layoutElement);
		}
	}
	
	private function setNewPosition($element, $transX, $transZ, $layoutElement) {
		$size = $layoutElement->getSize();
		
		// layout node position
		$nodePosition = $element->getTranslation();
		$nodePosition[0] = $nodePosition[0] + $transX - $size[0] / 2;
		$nodePosition[2] = $nodePosition[2] + $transZ - $size[1] / 2;
		
		$element->saveTranslation($nodePosition);
		
		return $nodePosition;
	}
}