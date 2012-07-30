<?php

abstract class AbstractView extends CApplicationComponent {
	
	public $layoutId;
	
	protected $layerMargin;
	
	public function calculate($layerLayout, $comp) {
		$this->adjustLayoutToX3d($layerLayout, $comp->level, $comp->id);
	}
	
	public function getLayerMargin() {
		return $this->layerMargin;
	}
	
	protected function adjustLayoutToX3d($layerLayout, $depth, $inputTreeElementId) {
		$this->adjustBb($layerLayout, $depth, $inputTreeElementId);
	
		foreach ($layerLayout['content'] as $key => $value) {
				if ($value['attributes']['type'] == InputTreeElement::$TYPE_NODE) {
					$this->adjustNode($value);
				} else {
					$this->adjustLeaf($value);
				}
		}
		
		foreach ($layerLayout['edges'] as $key => $value) {
			$this->adjustEdge($value);
		}
	}
	
	protected abstract function adjustBb($layerLayout, $depth, $inputTreeElementId);
	
	protected abstract function adjustNode($node);
	
	protected abstract function adjustLeaf($node);
	
	protected abstract function adjustEdge($node);
	
	public function getAllChildInputNodes($parentId) {
		return InputNode::model()->findAllByAttributes(array('parentId'=>$parentId));
	}
	
	public function getAllChildInputLeaves($parentId) {
		return InputLeaf::model()->findAllByAttributes(array('parentId'=>$parentId));
	}
	
	public function getAllChildInputDependencies($parentId) {
		return InputDependency::model()->findAllByAttributes(array('parentId'=>$parentId));
	}
	
}