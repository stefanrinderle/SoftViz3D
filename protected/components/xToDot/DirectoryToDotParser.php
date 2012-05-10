<?php

class DirectoryToDotParser extends CApplicationComponent
{
	
	public function parseToFile($path, $outputFile) {
		$lines = $this->getLines($path);
		
		$this->writeLinesToFile($lines, $outputFile);
	}
	
	public function parseToDotString($path) {
		$lines = $this->getLines($path);

		return $this->writeLinesToString($lines, $outputFile);
	}
	
	private function getLines($path) {
		$it = new DirectoryIterator($path);
		$directoryArray = $this->directoryIteratorToArray($it);
		
		return $this->createDotFileLines($directoryArray);
	}
	
	private function directoryIteratorToArray(DirectoryIterator $it) {
		$result = array();
		foreach ($it as $key => $child) {
			if ($child->isDot()) {
				continue;
			}
			$name = $child->getBasename();
			if ($child->isDir()) {
				$subit = new DirectoryIterator($child->getPathname());
				$result[$name] = $this->directoryIteratorToArray($subit);
			} else {
				$result[] = $name;
			}
		}
		return $result;
	}
	
	private function createDotFileLines($array, $firstLevel=true) {
		$result = array();
		
		if ($firstLevel) {
			array_push($result, 'digraph G {');
		}
		
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				// subgraph
				array_push($result, 'subgraph ' . str_replace(".", "X", str_replace("-", "X", $key)) . ' {');
				
				$result = array_merge($result, $this->createDotFileLines($value, false));
				
				array_push($result, '}');
			} else {
				// Knoten
				array_push($result, str_replace(".", "X", str_replace("-", "X", $value)) . ';');
			}
		}
		
		if ($firstLevel) {
			array_push($result, '}');
		}
		
		return $result;
	}
	
	private function writeLinesToFile($data, $fname) {
		$fp = fopen($fname, "w");
	
		foreach ($data as $key => $value) {
			fwrite($fp, "$value\n");
		}
	
		fclose($fp);
	}
	
	private function writeLinesToString($data) {
		$result = "";
	
		foreach ($data as $key => $value) {
			$result .= $value . "\n";
		}
	
		return $result;
	}
}