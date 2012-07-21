<?php

class GraphX3dCalculator extends AbstractX3dCalculator {
	
	private $nodeHeight = 10;
	private $layerSpacing = -200;
	
	protected function adjustBb($layerLayout, $depth) {
		$bb = $layerLayout['attributes']['bb'];
	
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
		$position = $node['attributes']['pos'];
		
		$result = array(
				'name'=>$node['id'],
				'size'=>array('width'=> $node['attributes']['width'] * LayoutVisitor::$SCALE,
						'height'=> $this->nodeHeight / 2,
						'length' => $node['attributes']['height'] * LayoutVisitor::$SCALE),
				'position'=>array('x' => $position[0],
						'y' => $depth * $this->layerSpacing,
						'z' => $position[1]),
				//'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
				'transparency'=>0.3,
				'isLeaf' => 0,
				'id' => "node_" . $node['id']
		);
	
		return $result;
	}
	
	protected function adjustLeaf($node, $depth) {
		if (substr($node['id'], 0, 4) == "dep_") {
			return $this->adjustDepLeaf($node, $depth);
		} else {
			$position = $node['attributes']['pos'];
			
			/**
			 * Only one metric! and the metric is set in 2d layout (side length...)
			 */
			$width = $node['attributes']['width'] * LayoutVisitor::$SCALE;
			
			// its a node with subnodes, so only specify the position and name.
			$result = array(
					'name'=>$node['id'],
					'size'=>array('width'=>$width, 'height'=>$this->nodeHeight, 'length'=>$width),
					'position'=>array('x' => $position[0],
							'y' => $depth * $this->layerSpacing + ($this->nodeHeight / 2),
							'z' => $position[1]),
					'colour'=>array('r'=>1, 'g'=>0.55, 'b'=>0),
					'transparency'=>0,
					'isLeaf' => 1,
					'id' => $node['id']
			);
			
			return $result;
		}
	}
	
	private function adjustDepLeaf($node, $depth) {
		$position = $node['attributes']['pos'];
		
		$height = abs($this->layerSpacing * 2) + $this->nodeHeight / 2;
		$side = $node['attributes']['width'] * LayoutVisitor::$SCALE;
	
		// its a node with subnodes, so only specify the position and name.
		$result = array(
				'name'=>$node['id'],
				'size'=>array('width'=> $side, 'height'=>$height, 'length'=>$side),
				'position'=>array('x' => $position[0],
						'y' => ($depth * $this->layerSpacing) + $height / 2,
						'z' => $position[1]),
				//'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
				'transparency'=>0.3,
				'isLeaf' => 0,
				'id' => $node['id']
		);
	
		return $result;
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