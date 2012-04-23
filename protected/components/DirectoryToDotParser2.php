<?php

class DirectoryToDotParser2 extends CApplicationComponent
{
	
	private $subgraphs;
	private $edges;
	
	public function parse($path, $outputFile) {
		$this->subgraphs = array();
		$this->edges = array();
		
		$directoryArray = $this->parseDirectoryToArray($path);
		
		if ($outputFile) {
			$this->createDotFile($directoryArray, $outputFile);
		}
	}
	
	private function createDotFileLines($array, $firstLevel=true) {
		$result = array();
		
		if ($firstLevel) {
			array_push($result, 'digraph G {');
			array_push($result, 'compound=true; nodesep=1.0;');
		}
		
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				// directory node
				array_push($result, $key . ';');
				
				//get a node out of array for the edge
				$node = array_slice($value, 0, 1);

				// subgraph
				$subgraph = array();

				array_push($subgraph, 'subgraph cluster_' . $key . ' {');
				$subgraph = array_merge($subgraph, $this->createDotFileLines($value, false));
				array_push($subgraph, '}');
				
				array_push($this->subgraphs, $subgraph);
				
				array_push($this->edges, $key . ' -> ' . str_replace(".", "_", $node[0]) . ' [lhead=cluster_' . $key . ']');
			} else {
				// Knoten
				array_push($result, str_replace(".", "_", $value) . ' [label="."];');
			}
		}
		
		if ($firstLevel) {
			foreach ($this->subgraphs as $key => $value) {
				$result = array_merge($result, $value);
			}
			
		foreach ($this->edges as $key => $value) {
				array_push($result, $value);
			}
			
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
	
	public function parseDirectoryToArray($path) {
		$it = new DirectoryIterator($path);
		
		return $this->directoryIteratorToArray($it);
	}
	
	private function createDotFile($array, $outputFile) {
		$lines = $this->createDotFileLines($array);
		
		$this->write_data($lines, $outputFile);
	}
}