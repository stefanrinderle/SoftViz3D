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
		$transparency = 0.2;
	
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
		$layoutId = 1;
		$inputDependencyId = $edge['attributes']['id'];
		
		$translation = array(0, 0, 0);
		
		$color = array('r'=>0, 'g'=>0, 'b'=>1);
		$lineWidth = $edge['attributes']['style'];
		
		$edgeElement = EdgeElement::createAndSaveEdgeElement($layoutId, $inputDependencyId, $translation, $color, $lineWidth);
		
		$positions = $edge['attributes']['pos'];
		
		$startPos = $this->setYAxis($positions[1], $depth);
		$endPos = $this->setYAxis($positions[0], $depth);
		
		if (count($positions) > 5) {
			// 0 and 1 are overall start points 
			$firstEndPoint = $this->setYAxis($positions[2], $depth);

			$element = $this->createSection($edgeElement->id, $startPos, $firstEndPoint);
			$element->save();
			
			for ($i = 3; $i < count($positions); $i++) {
				$sectionStart = $this->setYAxis($positions[$i - 1], $depth);
				$sectionEnd = $this->setYAxis($positions[$i], $depth);
				
				$element = $this->createSection($edgeElement->id, $sectionStart, $sectionEnd);
				$element->save();
			}
			
			$lastSectionPoint = $this->setYAxis($positions[count($positions) - 1], $depth);
			$endSection = $this->createSection($edgeElement->id, $lastSectionPoint, $endPos);
			
		} else {
			$endSection = $this->createSection($edgeElement->id, $startPos, $endPos);
		}
		
		$coneLength = $lineWidth * 3;
		$cylinderLength = $endSection->length - $coneLength;
		
		$endSection->type = EdgeSectionElement::$TYPE_END;
		$endSection->coneLength = $coneLength;
		$endSection->coneRadius = $lineWidth * 2;
		$endSection->cylinderLength = $cylinderLength;
		
		$endSection->save();
	}
	
	private function setYAxis($position, $depth) {
		$position['y'] = $depth * $this->layerSpacing;
		return $position;
	}
	
	private function createSection($edgeId, $startPos, $endPos) {
		$edgeVektor = VectorCalculator::vector($startPos, $endPos);
		$length = VectorCalculator::magnitude($edgeVektor);
		$rotation = VectorCalculator::rotationXAxis($edgeVektor);
		
		return EdgeSectionElement::createDefaultEdgeSectionElement($edgeId, $startPos, $rotation, $length);
	}
}