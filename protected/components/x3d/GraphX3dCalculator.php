<?php

class GraphX3dCalculator extends AbstractX3dCalculator {
	
	public function calculate($layerLayout, $comp) {
		parent::init();

		//TODO: calc on demand
		$this->layerDepth = -200;
		
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
					if (substr($value[label], 0, 4) == "dep_") {
						$nodes[$value['label']] = $this->adjustDepLeaf($value, $depth);
					} else {
						$nodes[$value['label']] = $this->adjustLeaf($value, $depth);
					}
				} else {
					$nodes[$value['label']] = $this->adjustNode($value, $depth);
				}
			}
		}
		$this->layout->nodes = $nodes;
		
		// Edges
		$edges = array();
		foreach ($layerLayout['content'] as $key => $value) {
			if ($value['type'] == "edge") {
				$edges[$value['label']] = $this->adustEdge($value, $depth);
			}
		}
		$this->layout->edges = $edges;
	}
	
	protected function adjustBb($layerLayout, $depth, $maxDepth) {
		//$randColor = rand(0, 100) / 100;
	
		$bb = $layerLayout['bb'];
	
		$width = $bb[2] - $bb[0];
		$length = $bb[3] - $bb[1];
	
		$colour = array('r'=>0, 'g'=> $depth * 0.2, 'b'=>0);
		$transpareny = 0.7;
	
		$result = array(
				'size'=>array('width'=>$width, 'length'=>$length),
				'colour'=>$colour,
				'position'=>array('x' => $bb[0],
						'y' => $depth * $this->layerDepth,
						'z' => $bb[1]),
				'transparency'=>$transpareny
		);
	
		return $result;
	}
	
	private function adjustDepLeaf($node, $depth) {
		$height = abs($this->layerDepth * 2) + parent::$DEFAULT_HEIGHT / 2;
		$side = $node['attr']['width'][0] * LayoutVisitor::$SCALE;
	
		// its a node with subnodes, so only specify the position and name.
		$result = array(
				'name'=>$node[label],
				'size'=>array('width'=> $side, 'height'=>$height, 'length'=>$side),
				'position'=>array('x' => $node['attr']['pos'][0],
						'y' => ($depth * $this->layerDepth) + $height / 2,
						'z' => $node['attr']['pos'][1]),
				'colour'=>array('r'=>1, 'g'=>0, 'b'=>0),
				'transparency'=>0,
				'isLeaf' => 0,
				'id' => $node['attr']['id']
		);
	
		return $result;
	}
	
	protected function adjustLeaf($node, $depth) {
		//METRIC
		/**
		 * Only one metric! and the metric is set in 2d layout (side length...)
		 */
		$width = $node[attr][width][0] * LayoutVisitor::$SCALE;
		
		$height = parent::$DEFAULT_HEIGHT;
		
		// its a node with subnodes, so only specify the position and name.
		$result = array(
				'name'=>$node[label],
				'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$width),
				'position'=>array('x' => $node['attr']['pos'][0],
						'y' => $depth * $this->layerDepth + ($height / 2),
						'z' => $node['attr']['pos'][1]),
				'colour'=>array('r'=>0, 'g'=>0, 'b'=>0.7),
				'transparency'=>0.2,
				'isLeaf' => 1,
				'id' => $node['attr']['id']
		);
	
		return $result;
	}
	
	protected function adjustNode($node, $depth) {
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=> $node[attr][width][0] * LayoutVisitor::$SCALE, 
								  'height'=> self::$DEFAULT_HEIGHT / 2, 
								  'length' => $node[attr][height][0] * LayoutVisitor::$SCALE),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerDepth,
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>0, 'g'=>0, 'b'=>1),
					'transparency'=>0.7,
					'isLeaf' => 0
			);
	
		return $result;
	}
	
	private function adustEdge($edge, $depth) {
		$lineWidth = $edge['attr']['style'][0];
		$lineWidth = substr($lineWidth, strpos($lineWidth, "(") + 1, strlen($lineWidth) - strpos($lineWidth, "(") - 2);
		// i just tried what the best factor should be
		$lineWidth = $lineWidth * (2 * 3.14);
		// convert edge section points
		$sections = array();
		
		for ($i = 5; $i < 10; $i = $i + 2) {
			$section = array('x' => $edge['attr']['pos'][$i],
							 'y' => $depth * $this->layerDepth,
							 'z' => $edge['attr']['pos'][$i + 1]);
			
			array_push($sections, $section);
		}
	
		$result = array(
				'endPos'=>array('x' => $edge['attr']['pos'][1],
						'y' => $depth * $this->layerDepth,
						'z' => $edge['attr']['pos'][2]),
				'startPos'=>array('x' => $edge['attr']['pos'][3],
						'y' => $depth * $this->layerDepth,
						'z' => $edge['attr']['pos'][4]),
				'sections'=>$sections,
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0),
				'lineWidth'=>$lineWidth
		);
	
		return $result;
	}
}