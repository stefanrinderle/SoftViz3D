<?php

class GraphX3dCalculator extends AbstractX3dCalculator
{
	public function calculate($layerLayout, $depth, $maxDepth)
	{
		parent::init();

		$this->layerDepth = - 10;
		
		$this->adjustLayoutToX3d($layerLayout, $depth, $maxDepth);
	
		return $this->layout;
	}
	
	protected function adjustLayoutToX3d($layerLayout, $depth, $maxDepth) {
		parent::adjustLayoutToX3d($layerLayout, $depth, $maxDepth);
		
		// Edges
		$edges = array();
		foreach ($layerLayout['content'] as $key => $value) {
			if ($value['type'] == "edge") {
				$edges[$value['label']] = $this->adustEdge($value, $depth);
			}
		}
		$this->layout->edges = $edges;
	}
	
	protected function adjustNode($node, $depth) {
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=> 0, 'height'=> 0, 'length' => 0),
// 					'size'=>array('width'=> LayoutVisitor::$SCALE / 2, 
// 								  'height'=> $this->nodeHeight, 
// 								  'length' => LayoutVisitor::$SCALE / 2),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerDepth,
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>0.5, 'g'=>0.5, 'b'=>0.5),
					'transparency'=>0
			);
	
		return $result;
	}
	
	private function adustEdge($edge, $depth) {
		// 			// convert edge section points
		$sections = array();
		// 			for ($i = 2; $i < count($edge['pos']); $i++) {
		// 				$section = array('x' => $edge['pos'][$i][0],
		// 								 'y' => $depth * $depthMultiplicator,
		// 								 'z' => $edge['pos'][$i][1]);
	
		// 				array_push($sections, $section);
		// 			}
	
		$result = array(
				'startPos'=>array('x' => $edge['attr']['pos'][1],
						'y' => $depth * $this->layerDepth,
						'z' => $edge['attr']['pos'][2]),
				'endPos'=>array('x' => $edge['attr']['pos'][3],
						'y' => $depth * $this->layerDepth,
						'z' => $edge['attr']['pos'][4]),
				'sections'=>$sections,
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
		);
	
		return $result;
	}
}