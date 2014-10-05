<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class GoannaSnapshotToDotParser extends CApplicationComponent {
	private $graphViz;
	
	private $subgraphIdentifier = 0;
	
	public function parseToFile($snapshotFilesArray, $dependencies = array()) {
		$this->graphViz = new Image_GraphViz_Copy();
		
		$this->_scanDirectory($snapshotFilesArray);
		if (count($dependencies) > 0) {
			$this->_scanDependencies($dependencies);
		}
		
		$this->graphViz->saveParsedGraph(Yii::app()->basePath . Yii::app()->params['currentResourceFile']);

		return true;
	}
	
	private function _scanDependencies($dep) {
		foreach ($dep as $value) {
			$this->graphViz->addEdge(array($value['file_id'] => $value['depends_id']));
		}
	}
	
	private function _scanDirectory($filesArray, $parentLabel = "default") {
		$sum = 0;
		foreach ($filesArray['children'] as $key => $value) {
			
			if ($value['type'] == "ROOT") {
				$this->_scanDirectory($value, $value['id'] + "");
			} else if ($value['type'] == "DIRECTORY") {
				
				$name = $value['id']  + "";// . "_" . $this->subgraphIdentifier++;
				$label = $value['name'] . "";
				
				$this->_addSubgraph($name, $label, $parentLabel);
				
				$this->_scanDirectory($value, $name);
			} else if ($value['type'] == "FILE") {
				$sum = 0;
				foreach($value['metrics'] as $metric) {
					$sum += $metric['value'];
				}
				
				$id = $value['id'];
				$name = $value['name'];
				
				$this->_addNode($id, $name, $sum, $parentLabel);
			}
		}
	}
	
	private function _addNode($id, $name, $warningCount, $parentId = 'default') {
		$label = str_replace("-", "_", $name);
		$this->graphViz->addNode($id, array('label' => $name, 'metric1' => $warningCount), $parentId);
	}
	
	private function _addSubgraph($name, $label, $parentId = 'default') {
		$label = str_replace("-", "_", $label);
		//TODO: cluster or subgraph
		//void addSubgraph( string $id, array $title, [array $attributes = array()], [string $group = 'default'])
		$this->graphViz->addSubgraph($name, $label, array(), $parentId);
	}
	
}