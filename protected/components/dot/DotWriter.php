<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class DotWriter extends CApplicationComponent {
	private $graphViz;
	
	public function writeToFile($elements, $outputFile, $maxDependencyCounter) {
		//$directed = true, $attributes = array(), $name = 'G', $strict = false, $returnError = false
		$attr = array();
// 		$attr['mindist'] = 0.5;
		
		$this->graphViz = new Image_GraphViz_Copy(true, $attr);
		
		$this->writeElements($elements, $maxDependencyCounter);
	
		$this->graphViz->saveParsedGraph($outputFile);
	}
	
	private function writeElements($elements, $maxDependencyCounter) {
		foreach ($elements as $key => $value) {
			if ($value instanceOf InputTreeElement) {
				$attr = array();
				$attr['shape'] = "rectangle";
								
				$attr['width'] = $value->proposedSize['width'];
				$attr['height'] = $value->proposedSize['height'];

				$attr['fixedsize'] = "true";
				$attr['id'] = $value->id;
				
				$attr['type'] = $value->type;
				
				if ($value instanceOf InputLeaf) {
					$attr['metric1'] = $value->metric1;
					$attr['metric2'] = $value->metric2;
				}
				
				$this->graphViz->addNode($value->name, $attr);
			} else if ($value instanceOf InputDependency) {
// 				if ($value->counter != 1) {
					$width = ($value->counter / $maxDependencyCounter) * 10;
					$width = 1 + $width;
					
					$name1 = $value->outElement->name;
					$name2 = $value->inElement->name;
					
					$this->graphViz->addEdge(array($name1 => $name2),
							array('id' => $value->id,
									'style' => 'setlinewidth(' . $width . ')'));
// 				}
			}
		}
	}

}