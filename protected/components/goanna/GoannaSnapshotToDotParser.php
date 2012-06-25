<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class GoannaSnapshotToDotParser extends CApplicationComponent
{
	private $graphViz;
	
	private $subgraphIdentifier = 0;
	
	public function parseToFile($snapshotFilesArray) {
		
		$this->graphViz = new Image_GraphViz_Copy();
// 		$this->graphViz->graph['name'] = str_replace("/", "_", $filesArray);
		
		$this->_scanDirectory($snapshotFilesArray);
		
// 		print_r($snapshotFilesArray);
		
		$this->graphViz->saveParsedGraph(Yii::app()->basePath . Yii::app()->params['currentResourceFile']);

		return true;
	}
	
	private function _scanDirectory($filesArray, $parentLabel = "default") {
		$sum = 0;
		foreach ($filesArray[children] as $key => $value) {
			
			if ($value[type] == "ROOT") {
				$this->_scanDirectory($value, $value[id] + "");
			} else if ($value[type] == "DIRECTORY") {
				
				$name = $value[id]  + "";// . "_" . $this->subgraphIdentifier++;
				
				$this->_addSubgraph($name, $parentLabel);
				
				$this->_scanDirectory($value, $name);
			} else if ($value[type] == "FILE") {
				$sum = 0;
				foreach($value[metrics] as $metric) {
					$sum += $metric[value];
				}
				
				$this->_addNode($value[id], $sum, $parentLabel);
			}
		}
	}
	
	private function _addNode($label, $warningCount, $parentId = 'default') {
		$label = str_replace("-", "_", $label);
		$this->graphViz->addNode($label, array(metric1 => $warningCount), $parentId);
	}
	
	private function _addSubgraph($label, $parentId = 'default') {
		$label = str_replace("-", "_", $label);
		//TODO: cluster or subgraph
		//void addSubgraph( string $id, array $title, [array $attributes = array()], [string $group = 'default'])
		$this->graphViz->addCluster($label, $label, array(), $parentId);
	}
	
}