<?php

abstract class AbstractX3dCalculator extends CApplicationComponent
{
	protected $layout;
	
	public function init() {
		$this->layout = new LayerLayout();
	}
	
	public function calculate($layerLayout, $comp) {
		$this->adjustLayoutToX3d($layerLayout, $comp->level, $comp->id);
	
		return $this->layout;
	}
	
	protected function adjustLayoutToX3d($layerLayout, $depth, $inputTreeElementId) {
		$this->layout->bb = $this->adjustBb($layerLayout, $depth, $inputTreeElementId);
	
		$nodes = array();
		$edges = array();
		
		foreach ($layerLayout['content'] as $key => $value) {
				if ($value['attributes']['type'] == "node") {
					$nodes[$value['id']] = $this->adjustNode($value, $depth);
				} else {
					$nodes[$value['id']] = $this->adjustLeaf($value, $depth);
				}
		}
		
		foreach ($layerLayout['edges'] as $key => $value) {
			$edges[$value['id']] = $this->adjustEdge($value, $depth);
		}
		
		$this->layout->nodes = $nodes;
		$this->layout->edges = $edges;
	}
	
	protected abstract function adjustBb($layerLayout, $depth, $inputTreeElementId);
	
	protected abstract function adjustNode($node, $depth);
	
	protected abstract function adjustLeaf($node, $depth);
	
	protected abstract function adjustEdge($node, $depth);
}