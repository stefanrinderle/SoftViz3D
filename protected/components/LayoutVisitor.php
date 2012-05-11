<?php
class LayoutVisitor {
	private static $SCALE = 72;
	private $outputFile = '/Users/stefan/Sites/3dArch/x3d/temp.dot';

	private $max_level = 0;

	function visitTreeElement(TreeElement $comp, $layoutElements) {
		// create layout array
		$layerLayout = $this->calcLayerLayout($layoutElements);

		// generate x3d code for this layer
		$comp->x3dInfos = Yii::app()->x3dCalculator->calculate($layerLayout, $comp->level, $this->max_level);

		// size of the node is the size of its bounding box
		$comp->size = array(width=>$layerLayout['bb'][2] / self::$SCALE, height=>$layerLayout['bb'][3] / self::$SCALE);

		return $comp;
	}

	function visitLeafTreeElement(TreeElement $comp) {
		if ($this->max_level < $comp->level) $this->max_level = $comp->level;

		$comp->size = array(width=>1, height=>1);
		return $comp;
	}

	/**
	 * Writes the current elements in an dot file and generated the layout dot file
	 */
	private function calcLayerLayout($elements) {
		Yii::app()->dotWriter->writeToFile($elements, $this->outputFile);
		$layoutDot = Yii::app()->dotLayout->layout($this->outputFile);

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
		
// 		print_r($newLayout);
// 		print_r("<br /><br /><br /><br />");
		
		return $newLayout;
	}
}
?>