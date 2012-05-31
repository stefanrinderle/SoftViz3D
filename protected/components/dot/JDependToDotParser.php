<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class JDependToDotParser extends CApplicationComponent
{
	
	private $graphViz;
	private $currentPackageName;
	
	public function parseToFile($inputFile, $outputFile) {
		$this->graphViz = new Image_GraphViz_Copy();
		
		// load as file
		$root = new SimpleXMLElement($inputFile,null,true);
		
		$this->parsePackages($root->Packages);
		
		$this->graphViz->saveParsedGraph($outputFile);
		
		return true;
	}
	
	private function getParentString($string) {
		$parentString = 'default';
	
		$packageNameArray = explode(".", $string);
		if (count($packageNameArray) > 1) {
			// it is a sub package
			$parentString = "";
			for ($i = 0; $i < count($packageNameArray) - 1; $i++) {
				if ($parentString != "") {
					$parentString .= ".";
				}
				$parentString .= $packageNameArray[$i];
			}
		}
	
		return $parentString;
	}
	
	private function checkForParentSubgraph($packageName) {
		$parentString = $this->getParentString($packageName);
	
		if (!$this->graphViz->_subgraphExists($parentString)) {
			if ($parentString != 'default') {
				$granny = $this->checkForParentSubgraph($parentString);
			}
			$this->_addSubgraph($parentString, $granny);
		}
	
		return $parentString;
	}
	
	private function parsePackages($packages) {
		foreach($packages->children() as $package) {
	
			$parentString = $this->checkForParentSubgraph($package[name]);
			$this->_addSubgraph($package[name], $parentString);
	
			$this->currentPackageName = $package[name];
	
			$childs = $package->children();
	
			if ($childs->AbstractClasses) {
				foreach ($childs->AbstractClasses->children() as $class) {
					$this->parseClass($class);
				}
			}
	
			if ($childs->ConcreteClasses) {
				foreach ($childs->ConcreteClasses->children() as $class) {
					$this->parseClass($class);
				}
			}
	
			if ($childs->DependsUpon) {
				foreach ($childs->DependsUpon->children() as $depends) {
					$this->parseDependency($depends);
				}
			}
		}
	}
	
	private function parseDependency($depends) {
		$string = (string) $this->currentPackageName;
		$stringDepend = (string) $depends;
		$this->graphViz->addEdge(array("cluster_" . $stringDepend => "cluster_" . $string));
	}
	
	private function parseClass($class) {
		$this->_addNode($class[sourceFile], $this->currentPackageName);
	}
	
	private function _addNode($label, $parentId = 'default') {
		$label = str_replace(array("-", "\\"), "_", $label);
		$parentId = str_replace(array("-", "\\"), "_", $parentId);
	
		$this->graphViz->addNode($label, array(), $parentId);
	}
	
	private function _addSubgraph($label, $parentId = 'default') {
		$label = str_replace(array("-", "\\"), "_", $label);
	
		//TODO: cluster or subgraph
		//void addSubgraph( string $id, array $title, [array $attributes = array()], [string $group = 'default'])
		$this->graphViz->addCluster($label, $label, array(), $parentId);
	}
}