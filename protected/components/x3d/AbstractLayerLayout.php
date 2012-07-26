<?php

abstract class AbstractLayerLayout extends CApplicationComponent {
	
	public function calculate($layerLayout, $comp) {
		$this->adjustLayoutToX3d($layerLayout, $comp->level, $comp->id);
	}
	
	protected function adjustLayoutToX3d($layerLayout, $depth, $inputTreeElementId) {
		$this->adjustBb($layerLayout, $depth, $inputTreeElementId);
	
		foreach ($layerLayout['content'] as $key => $value) {
				if ($value['attributes']['type'] == "node") {
					$this->adjustNode($value, $depth);
				} else {
					$this->adjustLeaf($value, $depth);
				}
		}
		
		foreach ($layerLayout['edges'] as $key => $value) {
			$this->adjustEdge($value, $depth);
		}
	}
	
	protected abstract function adjustBb($layerLayout, $depth, $inputTreeElementId);
	
	protected abstract function adjustNode($node, $depth);
	
	protected abstract function adjustLeaf($node, $depth);
	
	protected abstract function adjustEdge($node, $depth);
}