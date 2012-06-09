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
		
		$this->graphViz->saveParsedGraph(Yii::app()->basePath . Yii::app()->params['currentResourceFile']);

		return true;
	}
	
	private function _scanDirectory($filesArray, $parentLabel = "default") {
		foreach ($filesArray[children] as $key => $value) {
			
			if ($value[type] == "ROOT") {
				$this->_scanDirectory($value, $value[name]);
			} else if ($value[type] == "DIRECTORY") {
				
				$this->_addSubgraph($value[name], $parentLabel);
				
				$this->_scanDirectory($value, $value[name]);
			} else if ($value[type] == "FILE") {
				
				$this->_addNode($value[name], $parentLabel);
			}
		}
	}
	
	private function _addNode($label, $parentId = 'default') {
		$label = str_replace("-", "_", $label);
		$this->graphViz->addNode($label, array(), $parentId);
	}
	
	private function _addSubgraph($label, $parentId = 'default') {
		$label = str_replace("-", "_", $label);
		//TODO: cluster or subgraph
		//void addSubgraph( string $id, array $title, [array $attributes = array()], [string $group = 'default'])
		$this->graphViz->addCluster($label, $label, array(), $parentId);
	}
	
}