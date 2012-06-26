<?php

class TreeX3dCalculator extends AbstractX3dCalculator {
	
	protected function adjustBb($layerLayout, $depth, $maxDepth) {
		$bb = $layerLayout['bb'];
	
		$width = round($bb[2] - $bb[0], 2);
		$length = round($bb[3] - $bb[1], 2);
			
		$colourCalc = 0.2 + $depth * 0.1; 
		if ($colourCalc > 1.0) {
			$colourCalc = 1.0;
		}
		$colour = array('r'=>$colourCalc, 'g'=> $colourCalc, 'b'=>0);
		$transpareny = 0;//0.9 - ($maxDepth - $depth) * 0.1;
	
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
	
	protected function adjustNode($node, $depth) {
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=> 0, 'height'=> 0, 'length' => 0),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerDepth,
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
					'transparency'=>0,
					'isLeaf' => 0
			);
	
		return $result;
	}
	
}