<?php

class TreeX3dCalculator extends AbstractX3dCalculator {
	
	private $layerSpacing = 5;
	
	protected function adjustBb($layerLayout, $depth) {
		//$bb = $layerLayout['bb'];
		$bb = $layerLayout['attributes']['bb'];
		
		$width = round($bb[2] - $bb[0], 2);
		$length = round($bb[3] - $bb[1], 2);
			
		$colourCalc = ($depth - 1) * 0.3;
		if ($colourCalc > 1.0) {
			$colourCalc = 1.0;
		}
	
		$colour = array('r'=>0.87 - $colourCalc, 'g'=> 1 - $colourCalc, 'b'=> 1);
		$transparency = 0;//0.9 - ($maxDepth - $depth) * 0.1;
	
		$result = array(
				'size'=>array('width'=>$width, 'length'=>$length),
				'colour'=>$colour,
				'position'=>array('x' => $bb[0],
						'y' => $depth * $this->layerSpacing,
						'z' => $bb[1]),
				'transparency'=>$transparency
		);
		
		$layoutId = 0;
		$translation = array($bb[0], $depth * $this->layerSpacing, $bb[1]);
		//$size = array($width, $length);
		$size = array($width + 10, $length + 10);
		//$color = $colour;
		$color = array('r'=>1, 'g'=> 0, 'b'=> 0);
		BoxElement::createAndSaveBoxElement($layoutId, $translation, $size, $color, $transparency);
	
		return $result;
	}
	
	protected function adjustNode($node, $depth) {
		$position = $node['attributes']['pos'];
		
		$result = array(
				'name'=>$node['id'],
				'size'=>array('width'=> 0, 'height'=> 0, 'length' => 0),
				'position'=>array('x' => $position[0],
						'y' => $depth * $this->layerSpacing,
						'z' => $position[1]),
				'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
				'transparency'=>0,
				'isLeaf' => 0
		);
	
		return $result;
	}
	
	protected function adjustLeaf($node, $depth) {
		$width = $node['attributes']['width'] * LayoutVisitor::$SCALE;
		// !!! METRIC CALCULATION FOR 3D LAYOUT
		/**
		 * If only one metric is given, it will be represented by the
		 * building volume. Therefore the side length is set in 2D and the
		 * same value will be set for the 3D height here. Given 2 Metrics, first is the side length
		 * second is the 3D height. Given none, default values.
		 */
		if (array_key_exists('metric1', $node['attributes']) &&
				array_key_exists('metric2', $node['attributes'])) {
			$height = round($metric2 * LayoutVisitor::$SCALE / 2);
		} else {
			$height = $width;
		}
	
		$position = $node['attributes']['pos'];
		
		// its a node with subnodes, so only specify the position and name.
		$result = array(
				'name'=>$node['id'],
				'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$width),
				'position'=>array('x' => $position[0],
						'y' => $depth * $this->layerSpacing + ($height / 2),
						'z' => $position[1]),
				'colour'=>array('r'=>1, 'g'=>0.55, 'b'=>0),
				'transparency'=>0,
				'isLeaf' => 1,
				'id' => $node['id']
		);
	
		return $result;
	}
	
	protected function adjustEdge($node, $depth) {
		// nothing to do here
	}
}