<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class DirectoryToDotParser extends CApplicationComponent
{
	private $graphViz;
	
	private $subgraphIdentifier = 0;
	
	private $includeDot = false;
	
	public function parseToFile($path, $outputFile, $includeDot) {
		$this->includeDot = $includeDot;
		
		$it = new DirectoryIterator($path);
		
		$this->graphViz = new Image_GraphViz_Copy();
// 		$this->graphViz->binPath = '/usr/local/bin/';
		$this->graphViz->graph['name'] = str_replace("/", "_", $it->getPath());
		
		$this->_scanDirectory($it);
		
		$this->graphViz->saveParsedGraph($outputFile);
	}
	
	private function _scanDirectory(DirectoryIterator $it, $parentLabel = "default") {
		foreach ($it as $key => $child) {
			
			if ($child->isDot()) {
				continue;
			}
			
			$name = $child->getBasename();
			
			if (!$this->includeDot && substr($name, 0, 1) == ".") {
				continue;	
			}
			
			if ($child->isDir()) {
				$subit = new DirectoryIterator($child->getPathname());
				
				$name = $name . "_" . $this->subgraphIdentifier++;
				
				$this->_addSubgraph($name, $parentLabel);
				$this->_scanDirectory($subit, $name);
			} else {
				$this->_addNode($name, $parentLabel);
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