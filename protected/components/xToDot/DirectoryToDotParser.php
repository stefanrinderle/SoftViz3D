<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class DirectoryToDotParser extends CApplicationComponent
{
	private $graphViz;
	
	public function parseToFile($path, $outputFile) {
		$it = new DirectoryIterator($path);
		
		
		$this->graphViz = new Image_GraphViz_Copy();
		$this->graphViz->binPath = '/usr/local/bin/';
		$this->graphViz->graph['name'] = $it->getPath();
		
		$this->_scanDirectory($it);
		
		$this->graphViz->saveParsedGraph($outputFile);
	}
	
	private function _scanDirectory(DirectoryIterator $it, $parentLabel = "default") {
		foreach ($it as $key => $child) {
			if ($child->isDot()) {
				continue;
			}
			$name = $child->getBasename();
			
			if ($child->isDir()) {
				$subit = new DirectoryIterator($child->getPathname());
				$this->_addSubgraph($name, $parentLabel);
				$this->_scanDirectory($subit, $name);
			} else {
				$this->_addNode($name, $parentLabel);
			}
		}
	}
	
	private function _addNode($label, $parentId = 'default') {
		$this->graphViz->addNode($label, array(), $parentId);
	}
	
	private function _addSubgraph($label, $parentId = 'default') {
		//TODO: cluster or subgraph
		//void addSubgraph( string $id, array $title, [array $attributes = array()], [string $group = 'default'])
		$this->graphViz->addCluster($label, $label, array(), $parentId);
	}
	
}