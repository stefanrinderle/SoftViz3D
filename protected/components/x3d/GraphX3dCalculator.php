<?php

class GraphX3dCalculator extends AbstractX3dCalculator
{
	public function calculate($layerLayout, $comp)
	{
		parent::init();

		$this->layerDepth = -100;
		
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
		$transpareny = 0.7 - ($maxDepth - $depth) * 0.1;
	
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
		$height = abs($this->layerDepth * 2) + $this->nodeHeight / 2;
		$side = $node['attr']['width'][0] * LayoutVisitor::$SCALE  / 2;
	
		// its a node with subnodes, so only specify the position and name.
		$result = array(
				'name'=>$node[label],
				'size'=>array('width'=> $side,
						'height'=> $height,
						'length'=> $side),
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
				'isLeaf' => 1,
				'id' => $node['attr']['id']
		);
	
		return $result;
	}
	
	protected function adjustNode($node, $depth) {
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=> $node[attr][width][0] * LayoutVisitor::$SCALE, 
								  'height'=> $this->nodeHeight / 2, 
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
		
		$lineWidth = 1;
		
		// convert edge section points
		$sections = array();
		
		for ($i = 5; $i < 10; $i = $i + 2) {
			$section = array('x' => $edge['attr']['pos'][$i],
							 'y' => $depth * $this->layerDepth,
							 'z' => $edge['attr']['pos'][$i + 1]);
			
			array_push($sections, $section);
		}
	
		$sections = array_reverse($sections);
		
		$result = array(
				'startPos'=>array('x' => $edge['attr']['pos'][1],
						'y' => $depth * $this->layerDepth,
						'z' => $edge['attr']['pos'][2]),
				'endPos'=>array('x' => $edge['attr']['pos'][3],
						'y' => $depth * $this->layerDepth,
						'z' => $edge['attr']['pos'][4]),
				'sections'=>$sections,
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0),
				'lineWidth'=>$lineWidth
		);
	
		return $result;
	}
}