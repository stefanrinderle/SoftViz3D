<?php

class X3dGenerator extends CApplicationComponent
{
	
	public function generate($inputFile)
	{
		// get layout and parse result --------------------
		$graph =  Yii::app()->dotParser->parse($inputFile);
		
		// map to x3d -------------------------------------
		$x3dContent = array();
		
		$x3dContent = $this->adjustGraphToX3d($graph);
		
		//$translation = array('x'=> (- ($x3dContent['main']['bb']['size']['width'] / 2)),
		//					 'y'=> 0,
		//					 'z'=> (- ($x3dContent['main']['bb']['size']['length'] / 2)));
		
		//$this->render('index', array(translation=>$translation, content=>$x3dContent));
		
		return $x3dContent;
	}
	
	private function adjustGraphToX3d($graph) {
		$result = array(); 
		// Bounding Box
		$result['bb'] = $this->adjustBb($graph['bb']);
		
		// Nodes
		$result['nodes'] = array();
		foreach ($graph['nodes'] as $key => $value) {
			$result['nodes'][$key] = $this->adjustNode($key, $value);
		}
		
		// Edges
		$result['edges'] = array();
		foreach ($graph['edges'] as $key => $value) {
			$result['edges'][$key] = $this->adustEdge($value);
		}
		
		return $result;
	} 
	
	private function adjustBb($bb) {
		$width = $bb[2] - $bb[0];
		$length = $bb[3] - $bb[1];
		
		$colourValue = 1;
		$colour = array('r'=>0, 'g'=>$colourValue, 'b'=>0);
		$height = 1;
		$transpareny = 0;
		
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
	
	private function adjustNode($name, $node) {
		if ($node[type] == "leaf") {
			$result = array(
				'name'=>$name,
				'size'=>array('width'=>$node['size']['width'] * 50, 'height'=>10, 'length'=>$node['size']['height'] * 50),
				'position'=>array('x' => $node['pos'][0], 
								  'y' => 0, 
								  'z' => $node['pos'][1]),
				'colour'=>array('r'=>(rand(0, 100) / 100), 'g'=>(rand(0, 100) / 100), 'b'=>(rand(0, 100) / 100)),
				'transparency'=>0
			);
			
			return $result;
		} else {
			$result = array(
				'name'=>$name,
				//'size'=>array('width'=>$node['size']['width'] * 50, 'height'=>10, 'length'=>$node['size']['height'] * 50),
				'position'=>array('x' => $node['pos'][0], 
								  'y' => 0, 
								  'z' => $node['pos'][1]),
				//'colour'=>array('r'=>(rand(0, 100) / 100), 'g'=>(rand(0, 100) / 100), 'b'=>(rand(0, 100) / 100)),
				//'transparency'=>0
			);
			
			return $result;
		}
	}
	
	private function adustEdge($edge) {
			// convert edge section points
			$sections = array();
			for ($i = 2; $i < count($edge['pos']); $i++) {
				$section = array('x' => $edge['pos'][$i][0], 
								 'y' => 0, 
								 'z' => $edge['pos'][$i][1]);
				
				array_push($sections, $section);
			}			
			
			$result = array(
				'startPos'=>array('x' => $edge['pos'][1][0], 
								  'y' => 0, 
								  'z' => $edge['pos'][1][1]),
				'endPos'=>array('x' => $edge['pos'][0][1], 
								'y' => 0, 
								'z' => $edge['pos'][0][2]),
				'sections'=>$sections,
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
			
			return $result;
	}
}