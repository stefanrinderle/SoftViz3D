<?php

class TreeX3dCalculator extends AbstractX3dCalculator {
	
	private $layerSpacing = 5;
	
	protected function adjustBb($layerLayout, $depth) {
		$bb = $layerLayout['bb'];
		//$bb = explode(",", $layerLayout["attributes"]['bb']);
		
		$width = round($bb[2] - $bb[0], 2);
		$length = round($bb[3] - $bb[1], 2);
			
		$colourCalc = ($depth - 1) * 0.3;
		if ($colourCalc > 1.0) {
			$colourCalc = 1.0;
		}
	
		$colour = array('r'=>0.87 - $colourCalc, 'g'=> 1 - $colourCalc, 'b'=> 1);
		$transpareny = 0;//0.9 - ($maxDepth - $depth) * 0.1;
	
		$result = array(
				'size'=>array('width'=>$width, 'length'=>$length),
				'colour'=>$colour,
				'position'=>array('x' => $bb[0],
						'y' => $depth * $this->layerSpacing,
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
						'y' => $depth * $this->layerSpacing,
						'z' => $node['attr']['pos'][1]),
				'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
				'transparency'=>0,
				'isLeaf' => 0
		);
	
		return $result;
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
						'y' => $depth * $this->layerSpacing + ($height / 2),
						'z' => $node['attr']['pos'][1]),
				'colour'=>array('r'=>1, 'g'=>0.55, 'b'=>0),
				'transparency'=>0,
				'isLeaf' => 1,
				'id' => $node['attr']['id']
		);
	
		return $result;
	}
	
	protected function adjustEdge($node, $depth) {
		// nothing to do here
	}
}