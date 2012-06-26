<?php

abstract class AbstractX3dCalculator extends CApplicationComponent
{
	protected $layout;
	
	protected $layerDepth = 10;
	
	protected static $DEFAULT_HEIGHT = 10;
	
	public function init() {
		$this->layout = new LayerLayout();
	}
	
	public function calculate($layerLayout, $comp) {
		$this->adjustLayoutToX3d($layerLayout, $comp->level, $comp->max_level);
		
		return $this->layout;
	}
	
	protected function adjustLayoutToX3d($layerLayout, $depth, $maxDepth) {
		// Bounding Box
		$this->layout->bb = $this->adjustBb($layerLayout, $depth, $maxDepth);
		
		// Nodes
		$nodes = array();
		foreach ($layerLayout['content'] as $key => $value) {
			if ($value['type'] == "node") {
				if ($value['attr'][type] == "leaf") {
					$nodes[$value['label']] = $this->adjustLeaf($value, $depth);
				} else {
					$nodes[$value['label']] = $this->adjustNode($value, $depth);
				}
			}
		}
		$this->layout->nodes = $nodes;
	} 
	
	protected function adjustLeaf($node, $depth) {
			$width = $node[attr][width][0] * LayoutVisitor::$SCALE;
			// !!! METRIC CALCULATION FOR 3D LAYOUT
 			/**
 			 * If only one metric is given, it will be represented by the 
 			 * building volume. Therefore the side length is set in 2D and the 
 			 * same value will be set for the 3D height here. Given 2 Metrics, first is the side length
 			 * second is the 3D height. Given none, default values.
 			 */
			$metric1 = $node[attr][metric1];
			$metric2 = $node[attr][metric2];
		
 			if ($metric1 != 0 && $metric2 != 0) {
 				$height = round($metric2 * LayoutVisitor::$SCALE / 2);
 			} else {
 				$height = $width;
 			}
		
			// its a node with subnodes, so only specify the position and name.
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$width),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerDepth + ($height / 2),
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>1, 'g'=>0, 'b'=>0),
					'transparency'=>0,
					'isLeaf' => 1,
					'id' => $node['attr']['id']
			);
	
		return $result;
	}
	
	protected abstract function adjustNode($node, $depth);
	
	protected abstract function adjustBb($layerLayout, $depth, $maxDepth);
	
}