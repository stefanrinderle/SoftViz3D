<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class DotWriter extends CApplicationComponent
{
	private $graphViz;
	
	public function writeToFile($elements, $outputFile) {
		$this->graphViz = new Image_GraphViz_Copy();
		
		$this->writeElements($elements, $normalize);
	
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
				
				$attr['width'] = $value->twoDimSize[width];
				$attr['height'] = $value->twoDimSize[height];

				$attr['fixedsize'] = "true";
				$attr['id'] = $value->id;
				
				if ($value instanceOf LayerElement) {
					$attr['type'] = "node";
				} else if ($value instanceOf LeafElement) {
					$attr['metric1'] = $value->metric1;
					$attr['metric2'] = $value->metric2;
					
					$attr['type'] = "leaf";
				}
				
				$this->graphViz->addNode($value->label, $attr);
				
			} else if ($value instanceOf EdgeElement) {
				$width = $value->counter;
				
				//TODO: /100 is bad if there are more than 100 dep on one node
				$this->graphViz->addEdge(array($value->outElement->label => $value->inElement->label),
							array('style' => 'setlinewidth(' . $width . ')',
								  'weight' => 'setlinewidth(' . $width / 100 . ')'));
			}
		}
	}

}