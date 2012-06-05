<?php

class LayerLayout {
	public $bb;
	public $nodes = array();
	public $edges = array();
}

abstract class AbstractX3dCalculator extends CApplicationComponent
{
	protected $layout;
	
	protected $layerDepth = 10;
	protected $nodeHeight = 10;
	
	public function init() {
		$this->layout = new LayerLayout();
	}
	
	public function calculate($layerLayout, $comp)
	{
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
			// its a node with subnodes, so only specify the position and name.
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=>LayoutVisitor::$SCALE / 2, 'height'=>$this->nodeHeight,
							'length'=>LayoutVisitor::$SCALE / 2),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerDepth,
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>0, 'g'=>0, 'b'=>0.5),
					'transparency'=>0,
					'isLeaf' => 1
			);
	
		return $result;
	}
	
	protected abstract function adjustNode($node, $depth);
	
	protected abstract function adjustBb($layerLayout, $depth, $maxDepth);
	
}