<?php

class LayerLayout {
	public $bb;
	public $nodes = array();
	public $edges = array();
}

class X3dCalculator extends CApplicationComponent
{
	private $layout;
	
	public function calculate($graph, $depth, $maxDepth)
	{
		$this->layout = new LayerLayout();
		
		$this->adjustGraphToX3d($graph, $depth, $maxDepth);
		
		return $this->layout;
	}
	
	private function adjustGraphToX3d($graph, $depth, $maxDepth) {
		// Bounding Box
		$this->layout->bb = $this->adjustBb($graph['bb'], $depth, $maxDepth);
		
		// Nodes
		$nodes = array();
		foreach ($graph['nodes'] as $key => $value) {
			$nodes[$key] = $this->adjustNode($key, $value, $depth);
		}
		$this->layout->nodes = $nodes;
		
		// Edges
		$edges = array();
		foreach ($graph['edges'] as $key => $value) {
			$edges[$key] = $this->adustEdge($value, $depth);
		}
		$this->layout->edges = $edges;
	} 
	
	private function adjustBb($bb, $depth, $maxDepth) {
		$width = $bb[2] - $bb[0];
		$length = $bb[3] - $bb[1];
		
		$colour = array('r'=>0, 'g'=>$depth * 0.2, 'b'=>0);
		$height = $depth * 10;//($maxDepth - $depth) * 20;
		$transpareny = 0;//0.9 - ($maxDepth - $depth) * 0.1;
		
		$result = array(
					'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$length),
					'colour'=>$colour,
					'position'=>array('x' => $bb[0], 
								  	  'y' => 0, 
								      'z' => $bb[1]),
					'transparency'=>$transpareny
		);
		
		return $result;
	}
	
	private function adjustNode($name, $node, $depth) {
		$nodeHeight = 10;
		
		if ($node[type] == "leaf") {
			$result = array(
				'name'=>$name,
				'size'=>array('width'=>$node['size']['width'] * 72 / 2, 'height'=>$nodeHeight, 'length'=>$node['size']['height'] * 72 / 2),
				'position'=>array('x' => $node['pos'][0], 
								  'y' => $nodeHeight / 2 + $depth * 10, 
								  'z' => $node['pos'][1]),
				'colour'=>array('r'=>0, 'g'=>0, 'b'=>0.5),
				'transparency'=>0
			);
		} else {
			// its a node with subnodes, so only specify the position and name.
			$result = array(
				//'name'=>$name,
				//'size'=>array('width'=>$node['size']['width'] * 50, 'height'=>10, 'length'=>$node['size']['height'] * 50),
				'position'=>array('x' => $node['pos'][0], 
								  'y' => $nodeHeight / 2 + $depth * 10, 
								  'z' => $node['pos'][1]),
				//'colour'=>array('r'=>(rand(0, 100) / 100), 'g'=>(rand(0, 100) / 100), 'b'=>(rand(0, 100) / 100)),
				//'transparency'=>0
			);
		}
		
		return $result;
	}
	
	private function adustEdge($edge, $depth) {
			// convert edge section points
			$sections = array();
			for ($i = 2; $i < count($edge['pos']); $i++) {
				$section = array('x' => $edge['pos'][$i][0], 
								 'y' => $depth * 3, 
								 'z' => $edge['pos'][$i][1]);
				
				array_push($sections, $section);
			}			
			
			$result = array(
				'startPos'=>array('x' => $edge['pos'][1][0], 
								  'y' => $depth * 3, 
								  'z' => $edge['pos'][1][1]),
				'endPos'=>array('x' => $edge['pos'][0][1], 
								'y' => $depth * 3, 
								'z' => $edge['pos'][0][2]),
				'sections'=>$sections,
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
			
			return $result;
	}
}