<?php

class DirectoryToDotParser extends CApplicationComponent
{
	
	private $lastNode;
	private $lastGraph;
	
	public function parse($path, $outputFile) {
		$directoryArray = $this->parseDirectoryToArray($path);
		
		if ($outputFile) {
			$this->createDotFile($directoryArray, $outputFile);
		}
	}
	
	private function parseDirectoryToArray($path) {
		$it = new DirectoryIterator($path);
		
		return $this->directoryIteratorToArray($it);
	}
	
	private function createDotFile($array, $outputFile) {
		$lines = $this->createDotFileLines($array);
		
		$this->write_data($lines, $outputFile);
	}
	
	private function createDotFileLines($array, $firstLevel=true) {
		$result = array();
		
		if ($firstLevel) {
			array_push($result, 'digraph G {');
		}
		
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				// subgraph
				array_push($result, 'subgraph cluster_' . $key . ' {');
				
				$result = array_merge($result, $this->createDotFileLines($value, false));
				
				array_push($result, '}');
			} else {
				// Knoten
				array_push($result, str_replace(".", "_", $value) . ';');
			}
		}
		
		if ($firstLevel) {
			array_push($result, '}');
		}
		
		return $result;
	}
	
	private function write_data($data, $fname) {
	  $fp = fopen($fname, "w");
	
	  foreach ($data as $key => $value) {
	    fwrite($fp, "$value\n");
	  }
	
	  fclose($fp);
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
}