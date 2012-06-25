<?php
class LayoutVisitor {
	public static $TYPE_TREE = "tree";
	public static $TYPE_GRAPH = "graph";
	
	private static $DEFAULT_SIDE_LENGTH = 100;
	
	public static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/protected/runtime/temp.dot';

	private $max_level = 0;

	private $type;
	
	function __construct($type) {
		$this->type = $type;
	}
	
	function visitLayerElement(LayerElement $comp, $layoutElements) {
		// create layout array
		$layerLayout = $this->calcLayerLayout($layoutElements);

		if ($this->type == LayoutVisitor::$TYPE_TREE) {
			$x3dInfos = Yii::app()->treeX3dCalculator->calculate($layerLayout, $comp);
		} else {
			$x3dInfos = Yii::app()->graphX3dCalculator->calculate($layerLayout, $comp);
		}
		
		$x3dInfos->id = $comp[id];
		$x3dInfos->depth = $comp[level];
		$comp->setX3dInfos($x3dInfos);
		
		// size of the node is the size of its bounding box
		$comp->twoDimSize = array(width=>$layerLayout['bb'][2] / self::$SCALE, height=>$layerLayout['bb'][3] / self::$SCALE);

		return $comp;
	}

	function visitLeafElement(LeafElement $comp) {
		if ($this->max_level < $comp->level) $this->max_level = $comp->level;

 		if (substr($comp->label, 0, 4) == "dep_") {
			$side = sqrt($comp->counter) / 4;
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
 				$side = round($metric1 / self::$SCALE, 2);
 			} else  if ($metric1) {
 				$side = round($metric1 / self::$SCALE, 2);
 			} else if ($metric2) {
 				$side = round($metric2 / self::$SCALE, 2);
 			} else {
 				// TODO: DEFAULT SIZE 
 				$side = round(self::$DEFAULT_SIDE_LENGTH / self::$SCALE, 2);
 			}
 		}
 		
		$comp->twoDimSize = array(width=>$side, height=>$side);
		return $comp;
	}

	/**
	 * Writes the current elements in an dot file and generated the layout dot file
	 */
	private function calcLayerLayout($elements) {
		Yii::app()->dotWriter->writeToFile($elements, $this->outputFile);
		
		$layout = "neato";
		foreach ($elements as $var) {
			if ($var instanceof EdgeElement) {
				$layout = "dot";
				break;
			}
		}
		
		$layoutDot = Yii::app()->dotCommand->execute($this->outputFile, $layout);

		$newLayout = Yii::app()->dotFileParser->parseStringArray($layoutDot);
		
		$contentResult = array();
		foreach ($newLayout[content] as $key => $value) {
			if ($value[label] == "graph") {
				$newLayout[bb] = $value[attr][bb];
			} else if ($value[label] == "node") {
				//TODO: extract overall node infos
			} else {
				array_push($contentResult, $value);
			}
		}
		$newLayout[content] = $contentResult;
		
		return $newLayout;
	}
}
?>