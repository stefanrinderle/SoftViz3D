<?php

class GraphX3dCalculator extends AbstractX3dCalculator {
	
	private $nodeHeight = 10;
	private $layerSpacing = -200;
	
	protected function adjustBb($layerLayout, $depth, $maxDepth) {
		//$randColor = rand(0, 100) / 100;
	
		$bb = $layerLayout['bb'];
	
		$width = $bb[2] - $bb[0];
		$length = $bb[3] - $bb[1];
	
		$colour = array('r'=>0.7, 'g'=> 1, 'b'=> 1);
		$transpareny = 0.2;
	
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
				'size'=>array('width'=> $node[attr][width][0] * LayoutVisitor::$SCALE,
						'height'=> $this->nodeHeight / 2,
						'length' => $node[attr][height][0] * LayoutVisitor::$SCALE),
				'position'=>array('x' => $node['attr']['pos'][0],
						'y' => $depth * $this->layerSpacing,
						'z' => $node['attr']['pos'][1]),
				//'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
				'transparency'=>0.3,
				'isLeaf' => 0,
				'id' => "node_" . $node['attr']['id']
		);
	
		return $result;
	}
	
	protected function adjustLeaf($node, $depth) {
		if (substr($node[label], 0, 4) == "dep_") {
			return $this->adjustDepLeaf($node, $depth);
		} else {
			/**
			 * Only one metric! and the metric is set in 2d layout (side length...)
			 */
			$width = $node[attr][width][0] * LayoutVisitor::$SCALE;
			
			// its a node with subnodes, so only specify the position and name.
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=>$width, 'height'=>$this->nodeHeight, 'length'=>$width),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerSpacing + ($this->nodeHeight / 2),
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>1, 'g'=>0.55, 'b'=>0),
					'transparency'=>0,
					'isLeaf' => 1,
					'id' => $node['attr']['id']
			);
			
			return $result;
		}
	}
	
	private function adjustDepLeaf($node, $depth) {
		$height = abs($this->layerSpacing * 2) + $this->nodeHeight / 2;
		$side = $node['attr']['width'][0] * LayoutVisitor::$SCALE;
	
		// its a node with subnodes, so only specify the position and name.
		$result = array(
				'name'=>$node[label],
				'size'=>array('width'=> $side, 'height'=>$height, 'length'=>$side),
				'position'=>array('x' => $node['attr']['pos'][0],
						'y' => ($depth * $this->layerSpacing) + $height / 2,
						'z' => $node['attr']['pos'][1]),
				//'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
				'transparency'=>0.3,
				'isLeaf' => 0,
				'id' => $node['attr']['id']
		);
	
		return $result;
	}
	
	protected function adjustEdge($edge, $depth) {
		$lineWidth = $edge['attr']['style'][0];
		$lineWidth = substr($lineWidth, strpos($lineWidth, "(") + 1, strlen($lineWidth) - strpos($lineWidth, "(") - 2);
		
		$startPos = array('x' => $edge['attr']['pos'][3],
						  'y' => $depth * $this->layerSpacing,
						  'z' => $edge['attr']['pos'][4]);
		$endPos = array('x' => $edge['attr']['pos'][1],
						'y' => $depth * $this->layerSpacing,
						'z' => $edge['attr']['pos'][2]);
		
		$sectionPoints = $this->getSectionPoints($edge['attr']['pos'], $depth);
		if (count($sectionPoints)) {
			$edgeSections = $this->createSections($sectionPoints, $startPos);
		 	
			$endSection = $this->createSection($sectionPoints[count($sectionPoints) - 1], $endPos);
		} else {
			$edgeSections = array();
			
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
				'id' => $edge['attr']['id'],
				'colour'=>array('r'=>1, 'g'=>0, 'b'=>0),
				'lineWidth'=>$lineWidth,
				'edgeSections' => $edgeSections,
				'endSection' => $endSection
		);
	
		return $result;
	}
	
	private function getSectionPoints($points, $depth) {
		$result = array();
		// curved edges have more than 11 point attributes in the output attributed dot file
		$straightEdgePointCount = 11;
		if (count($points) > $straightEdgePointCount) {
			for ($i = 5; $i < 10; $i = $i + 2) {
				$sectionPoint = array('x' => $points[$i],
						'y' => $depth * $this->layerSpacing,
						'z' => $points[$i + 1]);
					
				array_push($result, $sectionPoint);
			}
		}
		
		return $result;
	}
	
	private function createSections($sectionPoints, $startPos) {
		$result = array();
		$section = $this->createSection($startPos, $sectionPoints[0]);
		array_push($result, $section);
			
		for ($i = 1; $i < count($sectionPoints); $i++) {
			if ($sectionPoints[$i - 1] != $sectionPoints[$i]) {
				$section = $this->createSection($sectionPoints[$i - 1], $sectionPoints[$i]);
				array_push($result, $section);
			}
		}
	
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