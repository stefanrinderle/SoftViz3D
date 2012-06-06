<?php
class LayoutVisitor {
	public static $TYPE_TREE = "tree";
	public static $TYPE_GRAPH = "graph";
	
	public static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/protected/runtime/temp.dot';

	private $max_level = 0;

	private $type;
	
	function __construct($type) {
		$this->type = $type;
	}
	
	function visitTreeElement(TreeElement $comp, $layoutElements) {
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
		$comp->size = array(width=>$layerLayout['bb'][2] / self::$SCALE, height=>$layerLayout['bb'][3] / self::$SCALE);

		return $comp;
	}

	function visitLeafTreeElement(TreeElement $comp) {
		if ($this->max_level < $comp->level) $this->max_level = $comp->level;

 		if (substr($comp->label, 0, 4) == "dep_") {
			$side = sqrt($comp->counter) / 4;
 		} else {
 			$side = 1;
 		}
		
		$comp->size = array(width=>$side, height=>$side);
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