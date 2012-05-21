<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class DotWriter extends CApplicationComponent
{
	private $graphViz;
	
	public function writeToFile($elements, $outputFile) {
		$this->graphViz = new Image_GraphViz_Copy();
	
		$this->writeElements($elements);
	
		$this->graphViz->saveParsedGraph($outputFile);
	}
	
	public function writeToString($elements) {
		$this->graphViz = new Image_GraphViz_Copy();
		
		$this->writeElements($elements);
		
		return $this->graphViz->parse();
	}
	
	private function writeElements($elements) {
		foreach ($elements as $key => $value) {
			if ($value instanceOf TreeElement) {
				$attr = array();
				$attr['shape'] = "rectangle";
				$attr['width'] = $value->size[width];
				$attr['height'] = $value->size[height];
				$attr['fixedsize'] = "true";
				
				if ($value->isLeaf) {
					$attr['type'] = "leaf";
				} else {
					$attr['type'] = "node";
				}
				
				$this->graphViz->addNode($value->label, $attr);
				
			} else if ($value instanceOf EdgeElement) {
				$this->graphViz->addEdge(array($value->outElement->label => $value->inElement->label));
			}
		}
	}

}