<?php

abstract class AbstractLayerLayout extends CApplicationComponent {
	
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
				if ($value['attributes']['type'] == "node") {
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
}