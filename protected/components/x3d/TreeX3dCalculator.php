<?php

class TreeX3dCalculator extends AbstractX3dCalculator {
	
	private $layerSpacing = 5;
	
	protected function adjustBb($layerLayout, $depth, $inputTreeElementId) {
		$layoutId = 1;

		$bb = $layerLayout['attributes']['bb'];
		$width = round($bb[2] - $bb[0], 2);
		$length = round($bb[3] - $bb[1], 2);

		$translation = array(0, $depth * $this->layerSpacing, 0);
		$size = array($width, $length);
		
		$colorCalc = ($depth - 1) * 0.3;
		if ($colorCalc > 1.0) {
			$colorCalc = 1.0;
		}
		$color = array('r'=>0.87 - $colorCalc, 'g'=> 1 - $colorCalc, 'b'=> 1);
		$transparency = 0;
		
		BoxElement::createAndSaveBoxElement(
				$layoutId, $inputTreeElementId, BoxElement::$TYPE_PLATFORM, 
				$translation, $size, $color, $transparency);
	}
	
	protected function adjustNode($node, $depth) {
		$layoutId = 1;
		$inputTreeElementId = $node['attributes']['id'];
		
		$position = $node['attributes']['pos'];
		$translation = array($position[0], $depth * $this->layerSpacing, $position[1]);
		$size = array('width'=> 0, 'height'=> 0, 'length' => 0);
		
		$color = array('r'=>0, 'g'=>0, 'b'=>0);
		$transparency = 0;
		
		BoxElement::createAndSaveBoxElement(
				$layoutId, $inputTreeElementId, BoxElement::$TYPE_FOOTPRINT,
				$translation, $size, $color, $transparency);
	}
	
	protected function adjustLeaf($node, $depth) {
		$layoutId = 1;
		$inputTreeElementId = $node['attributes']['id'];
		
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
			$height = round($node['attributes']['metric2'] * LayoutVisitor::$SCALE / 2);
		} else {
			$height = $width;
		}
	
		$position = $node['attributes']['pos'];
		$translation = array($position[0], $depth * $this->layerSpacing  + ($height / 2), $position[1]);
		$size = array('width'=>$width, 'height'=>$height, 'length'=>$width);
		
		$color = array('r'=>1, 'g'=>0.55, 'b'=>0);
		$transparency = 0;
		
		BoxElement::createAndSaveBoxElement(
				$layoutId, $inputTreeElementId, BoxElement::$TYPE_BUILDING,
				$translation, $size, $color, $transparency);
	}
	
	protected function adjustEdge($node, $depth) {
		// nothing to do here
	}
}