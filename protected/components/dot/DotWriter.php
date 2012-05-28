<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class DotWriter extends CApplicationComponent
{
	private $graphViz;
	
	public function writeToFile($elements, $outputFile, $normalize = false) {
		$this->graphViz = new Image_GraphViz_Copy();
	
		$this->writeElements($elements, $normalize);
	
		$this->graphViz->saveParsedGraph($outputFile);
	}
	
	public function writeToString($elements) {
		$this->graphViz = new Image_GraphViz_Copy();
		
		$this->writeElements($elements);
		
		return $this->graphViz->parse();
	}
	
	private function writeElements($elements, $normalize = false) {
		foreach ($elements as $key => $value) {
			if ($value instanceOf TreeElement) {
				$attr = array();
				$attr['shape'] = "rectangle";
				if ($normalize) {
					$attr['width'] = 1;
					$attr['height'] = 1;
				} else {
					$attr['width'] = $value->size[width];
					$attr['height'] = $value->size[height];
				}
				
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