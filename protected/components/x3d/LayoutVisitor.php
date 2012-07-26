<?php
class LayoutVisitor {
	public static $TYPE_TREE = "tree";
	public static $TYPE_GRAPH = "graph";
	
	private static $DEFAULT_SIDE_LENGTH = 0.1;
	
	public static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/protected/runtime/temp.dot';

	private $max_level = 0;

	private $maxMetric1;
	private $maxMetric2;
	private $maxCounter;
	
	private $type;
	
	function __construct($type) {
		$this->type = $type;
		
		$criteria = new CDbCriteria;
		$criteria->select='MAX(metric1) as maxMetric1';
		$this->maxMetric1 = InputLeaf::model()->find($criteria)->maxMetric1;
		
		$criteria = new CDbCriteria;
		$criteria->select='MAX(metric2) as maxMetric2';
		$this->maxMetric2 = InputLeaf::model()->find($criteria)->maxMetric2;
		
		$criteria = new CDbCriteria;
		$criteria->select='MAX(counter) as maxCounter';
		$this->maxCounter = InputLeaf::model()->find($criteria)->maxCounter;
	}
	
	function visitInputNode(InputNode $comp, $layoutElements) {
		// create layout array
		$layerLayout = $this->calcLayerLayout($layoutElements);

		if ($this->type == LayoutVisitor::$TYPE_TREE) {
			$x3dInfos = Yii::app()->structureLayout->calculate($layerLayout, $comp);
		} else {
			$x3dInfos = Yii::app()->dependencyLayout->calculate($layerLayout, $comp);
		}
		
		$x3dInfos->id = $comp['id'];
		$x3dInfos->depth = $comp['level'];
		$comp->setX3dInfos($x3dInfos);
		
		$bb = $layerLayout['attributes']['bb'];
		
		// size of the node is the size of its bounding box
		$comp->twoDimSize = array('width' => $bb[2] / self::$SCALE, 'height' => $bb[3] / self::$SCALE);

		return $comp;
	}

	function visitInputLeaf(InputLeaf $comp) {
		if ($this->max_level < $comp->level) $this->max_level = $comp->level;

 		if (substr($comp->name, 0, 4) == "dep_") {
 			// value between 0 and 1
 			// this was to fat: $comp->counter / $this->maxCounter
 			//TODO: this should not be possible
 			if ($this->maxCounter != 0) {
 				$value = ($comp->counter / $this->maxCounter / 2) + 0.1;
 				$side = round($value, 2);
 			} else {
 				$side = 1;
 			}
 		} else {
 			if ($this->type == LayoutVisitor::$TYPE_GRAPH) {
				if ($this->maxCounter != 0) {
					$value = ($comp->counter / $this->maxCounter) + 0.1;
					$side = round($value, 2);
				} else {
					$side = self::$DEFAULT_SIDE_LENGTH;
				}
 			} else {
 				// !!! METRIC CALCULATION FOR 2D LAYOUT
 				$metric1 = $comp->metric1;
 				$metric2 = $comp->metric2;
 				
 				/**
 				 * If only one metric is given, it will be represented by the
 				 * building volume. Therefore the side length is set here and the
 				 * same value will be set for the 3D heigth later in the
 				 * X3dCalculators. Given 2 Metrics, first is the side length
 				 * second is the 3D height. Given none, default values.
 				 */
 				if ($metric1 && $metric2) {
 					$side = round($metric1 / $this->maxMetric1, 2);
 				} else  if ($metric1) {
 					$side = round($metric1 / $this->maxMetric1, 2);
 				} else if ($metric2) {
 					$side = round($metric2 / $this->maxMetric2, 2);
 				} else {
 					$side = self::$DEFAULT_SIDE_LENGTH;
 				}
 				
 				if($side < 0.05) {
 					$side = 0.05;
 				}
 			}
 		}
 		
		$comp->twoDimSize = array('width' => $side, 'height' => $side);
		return $comp;
	}

	/**
	 * Writes the current elements in an dot file and generated the layout dot file
	 */
	private function calcLayerLayout($elements) {
		Yii::app()->dotWriter->writeToFile($elements, $this->outputFile);
		
		$layout = "neato";
		if (count($elements) == 1) {
			$layout = "dot";
		} else {
			foreach ($elements as $var) {
				if ($var instanceof InputDependency) {
					$layout = "dot";
					break;
				}
			}
		}
		
		$layoutDot = Yii::app()->dotCommand->execute($this->outputFile, $layout);
		
		$newLayout = Yii::app()->dotArrayParser->parse($layoutDot);
		
		return $newLayout;
	}
}
?>