<?php

abstract class AbstractX3dCalculator extends CApplicationComponent
{
	protected $layout;
	
	public function init() {
		$this->layout = new LayerLayout();
	}
	
	public function calculate($layerLayout, $comp) {
		$this->adjustLayoutToX3d($layerLayout, $comp->level);
	
		return $this->layout;
	}
	
	protected function adjustLayoutToX3d($layerLayout, $depth) {
		$this->layout->bb = $this->adjustBb($layerLayout, $depth);
	
		$nodes = array();
		$edges = array();
		
		foreach ($layerLayout['content'] as $key => $value) {
			if ($value['type'] == "node") {
				if ($value['attr'][type] == "leaf") {
					$nodes[$value['label']] = $this->adjustLeaf($value, $depth);
				} else {
					$nodes[$value['label']] = $this->adjustNode($value, $depth);
				}
			} else if ($value['type'] == "edge") {
				$edges[$value['label']] = $this->adjustEdge($value, $depth);
			}
		}
		
		$this->layout->nodes = $nodes;
		$this->layout->edges = $edges;
	}
	
	protected abstract function adjustBb($layerLayout, $depth);
	
	protected abstract function adjustNode($node, $depth);
	
	protected abstract function adjustLeaf($node, $depth);
	
	protected abstract function adjustEdge($node, $depth);
}