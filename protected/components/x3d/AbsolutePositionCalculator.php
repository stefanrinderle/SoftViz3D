<?php

class AbsolutePositionCalculator extends CApplicationComponent {
	
	private $layout;
	
	public function calculate($rootId, AbstractLayerLayout $layout) {
		$this->layout = $layout;
		
		$this->addTranslationToLayer($rootId, array(0, 0, 0), 1);
	}
	
	private function addTranslationToLayer($rootId, $parentTranslation, $isRoot = false) {
		$layoutElement = BoxElement::model()->findByAttributes(array('inputTreeElementId'=>$rootId));

		$translation = $layoutElement->getTranslation();
		$translation[0] = $translation[0] + $parentTranslation[0];
		$translation[1] = $parentTranslation[1];
		$translation[2] = $translation[2] + $parentTranslation[2];
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
			
				$nodePosition = $this->setNewPosition($element, $parentTranslation, $layoutElement);
				
				$nodePosition[1] = $nodePosition[1] + $this->layout->getLayerMargin();
				
				$this->addTranslationToLayer($value->id, $nodePosition);
			} else {
				$element = BoxElement::model()->findByAttributes(array(
						'inputTreeElementId'=>$value->id,
						'type'=>BoxElement::$TYPE_BUILDING));

				// should be not the case after all
				if ($element) {
					$nodePosition = $this->setNewPosition($element, $parentTranslation, $layoutElement);
				}
			}
		}
		
		$contentEdges = InputDependency::model()->findAllByAttributes(array('parent_id' => $layoutElement->inputTreeElementId));
		foreach ($contentEdges as $key => $value) {
			$element = EdgeElement::model()->findByAttributes(array(
									'inputDependencyId'=>$value->id));
			
			// should be not the case after all
			if ($element) {
				$nodePosition = $this->setNewPosition($element, $parentTranslation, $layoutElement);
			}
		}
	}
	
	private function setNewPosition($element, $parentTranslation, $layoutElement) {
		$size = $layoutElement->getSize();
		
		// layout node position
		$nodePosition = $element->getTranslation();
		$nodePosition[0] = $nodePosition[0] + $parentTranslation[0] - $size[0] / 2;
		$nodePosition[1] = $parentTranslation[1];
		$nodePosition[2] = $nodePosition[2] + $parentTranslation[2] - $size[1] / 2;
		
		if ($element instanceof BoxElement) {
			if ($element->type == BoxElement::$TYPE_BUILDING) {
				$size = $element->getSize();
				$nodePosition[1] = $parentTranslation[1] + $size[1] / 2;
			} else {
				$nodePosition[1] = $parentTranslation[1];
			}
		}
		
		$element->saveTranslation($nodePosition);
		
		return $nodePosition;
	}
}