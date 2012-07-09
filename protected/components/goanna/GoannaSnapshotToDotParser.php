<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class GoannaSnapshotToDotParser extends CApplicationComponent
{
	private $graphViz;
	
	private $subgraphIdentifier = 0;
	
	public function parseToFile($snapshotFilesArray, $dependencies = array()) {
		$this->graphViz = new Image_GraphViz_Copy();
		
		$this->_scanDirectory($snapshotFilesArray);
		if (count($dependencies) > 0) {
			$this->_scanDependencies($dependencies);
		}
		
		$this->graphViz->saveParsedGraph(Yii::app()->basePath . Yii::app()->params['currentResourceFile']);

		print_r($this->graphViz);
		
		return true;
	}
	
	private function _scanDependencies($dep) {
		foreach ($dep as $value) {
			$this->graphViz->addEdge(array($value[file_id] => $value[depends_id]),
						array(label => $value[file] . "XXX" . $value[depends]));
		}
	}
	
	private function _scanDirectory($filesArray, $parentId = "default") {
		$parentId = $parentId . "";
		
		$sum = 0;
		foreach ($filesArray[children] as $key => $value) {
			if ($value[type] == "ROOT") {
				$this->_scanDirectory($value, "ROOT");
			} else if ($value[type] == "DIRECTORY") {
				
				$id = $value[id]  . "";
				$name = $value[name] . "";
				
				$this->_addSubgraph($id, $name, $parentId);
				
				$this->_scanDirectory($value, $id);
			} else if ($value[type] == "FILE") {
				$sum = 0;
				foreach($value[metrics] as $metric) {
					$sum += $metric[value];
				}
				
				$this->_addNode($value[id], $value[name], $sum, $parentId);
			}
		}
	}
	
	private function _addNode($id, $label, $warningCount, $parentId = "default") {
		$label = str_replace("-", "_", $label);
		$this->graphViz->addNode($id, array(label=> $label, metric1 => $warningCount), $parentId);
	}
	
	private function _addSubgraph($id, $label, $parentId) {
		$label = str_replace("-", "_", $label);
		$this->graphViz->addSubgraph($id, $label, array(), $parentId);
	}
	
}