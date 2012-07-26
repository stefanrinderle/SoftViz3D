<?php

class GraphX3dCalculator extends AbstractX3dCalculator {
	
	private $nodeHeight = 10;
	private $layerSpacing = -200;
	
	protected function adjustBb($layerLayout, $depth, $inputTreeElementId) {
		$layoutId = 1;
		
		$bb = $layerLayout['attributes']['bb'];
		$width = round($bb[2] - $bb[0], 2);
		$length = round($bb[3] - $bb[1], 2);
	
		$color = array('r'=>0.7, 'g'=> 1, 'b'=> 1);
		$transpareny = 0.2;
	
		$translation = array(0, $depth * $this->layerSpacing, 0);
		$size = array('width'=>$width, 'length'=>$length);
		
		BoxElement::createAndSaveBoxElement(
				$layoutId, $inputTreeElementId, BoxElement::$TYPE_PLATFORM,
				$translation, $size, $color, $transparency);
	}
	
	protected function adjustNode($node, $depth) {
		$layoutId = 1;
		$inputTreeElementId = $node['attributes']['id'];
		
		$position = $node['attributes']['pos'];
		$translation = array($position[0], $depth * $this->layerSpacing, $position[1]);
		$size = array($node['attributes']['width'] * LayoutVisitor::$SCALE, 
						$this->nodeHeight / 2, 
						$node['attributes']['height'] * LayoutVisitor::$SCALE);
	
		$color = array('r'=>1, 'g'=>0, 'b'=>0);
		$transparency = 0.3;
		
		BoxElement::createAndSaveBoxElement(
				$layoutId, $inputTreeElementId, BoxElement::$TYPE_FOOTPRINT,
				$translation, $size, $color, $transparency);
	}
	
	protected function adjustLeaf($node, $depth) {
		$layoutId = 1;
		$inputTreeElementId = $node['attributes']['id'];
		
		if (substr($node['id'], 0, 4) == "dep_") {
			$this->adjustDepLeaf($node, $depth);
		} else {
			$position = $node['attributes']['pos'];
			
			/**
			 * Only one metric! and the metric is set in 2d layout (side length...)
			 */
			$width = $node['attributes']['width'] * LayoutVisitor::$SCALE;
			
			$translation = array($position[0], $depth * $this->layerSpacing  + ($this->nodeHeight / 2), $position[1]);
			$size = array('width'=>$width, 'height'=>$this->nodeHeight, 'length'=>$width);
			
			$color = array('r'=>1, 'g'=>0.55, 'b'=>0);
			$transparency = 0;
			
			BoxElement::createAndSaveBoxElement(
					$layoutId, $inputTreeElementId, BoxElement::$TYPE_BUILDING,
					$translation, $size, $color, $transparency);
		}
	}
	
	private function adjustDepLeaf($node, $depth) {
		$layoutId = 1;
		$inputTreeElementId = $node['attributes']['id'];
		
		$position = $node['attributes']['pos'];
		
		$height = abs($this->layerSpacing);
		$side = $node['attributes']['width'] * LayoutVisitor::$SCALE;
	
		$translation = array('x' => $position[0],
						'y' => ($depth * $this->layerSpacing) + $height / 2,
						'z' => $position[1]);
		
		$size = array('width'=> $side, 'height'=>$height, 'length'=>$side);
		$color = array('r'=>0.2, 'g'=>0.2, 'b'=>0.2);
		$transparency = 0.3;
		
		BoxElement::createAndSaveBoxElement(
				$layoutId, $inputTreeElementId, BoxElement::$TYPE_BUILDING,
				$translation, $size, $color, $transparency);
	}
	
	protected function adjustEdge($edge, $depth) {
		$lineWidth = $edge['attributes']['style'];
		//TODO: regex
		$lineWidth = substr($lineWidth, strpos($lineWidth, "(") + 1, strlen($lineWidth) - strpos($lineWidth, "(") - 2);
		
		$position = $edge['attributes']['pos'];
		
		$startPos = array('x' => $position[1]['x'],
						  'y' => $depth * $this->layerSpacing,
						  'z' => $position[1]['z']);
		$endPos = array('x' => $position[0]['x'],
						'y' => $depth * $this->layerSpacing,
						'z' => $position[0]['z']);
		
		$edgeSections = array();
		if (count($position) > 5) {
			// 0 and 1 are overall start points 
			$firstEndPoint = $position[2];
			$firstEndPoint['y'] = $depth * $this->layerSpacing;

			$firstSection = $this->createSection($startPos, $firstEndPoint);
			array_push($edgeSections, $firstSection);
			
			for ($i = 3; $i < count($position); $i++) {
				$position[$i]['y'] = $depth * $this->layerSpacing;
				$position[$i-1]['y'] = $depth * $this->layerSpacing;
				
				$section = $this->createSection($position[$i - 1], $position[$i]);
				array_push($edgeSections, $section);
			}
			
			$lastSectionPoint = $position[count($position) - 1];
			$lastSectionPoint['y'] = $depth * $this->layerSpacing;
			$endSection = $this->createSection($lastSectionPoint, $endPos);
		} else {
			$endSection = $this->createSection($startPos, $endPos);
		}
		
		// add additional edge end information
		$mainEdgeHeight = VectorCalculator::magnitude($endSection['edgeVector']);
		$rotation = VectorCalculator::rotationXAxis($endSection['edgeVector']);
		$coneLength = $lineWidth * 3;
		$cylinderLength = $mainEdgeHeight - $coneLength;
		
		$endSectionInfos = array(
				"coneRadius" => $lineWidth * 2,
				"coneLength" => $coneLength,
				"cylinderLength" => $cylinderLength,
				"rotation" => $rotation
				);
		$endSection = array_merge($endSection, $endSectionInfos);
		
		$result = array(
				'id' => $edge['id'],
				'colour'=>array('r'=>1, 'g'=>0, 'b'=>0),
				'lineWidth'=>$lineWidth,
				'edgeSections' => $edgeSections,
				'endSection' => $endSection
		);
	
		return $result;
	}
	
	private function createSection($startPos, $endPos) {
		$edgeVektor = VectorCalculator::vector($startPos, $endPos);
		$length = VectorCalculator::magnitude($edgeVektor);
		$rotation = VectorCalculator::rotationXAxis($edgeVektor);
		
		return array(
				"startPos" => $startPos,
				"edgeVector" => $edgeVektor,
				"length" => $length,
				"rotation" => $rotation
				);
	}
}