<?php

/**
 * Parse an adot file given as a file path to a graph structure.
 */
class AdotFileParser extends AdotParser
{
	
	private $parseFileHandle;
	
	public function parse($adotFile)
	{
		$this->parseFileHandle = fopen($adotFile, "r");
		
		// ommit first line: digraph G {
		$this->getNewLine();
		// ommit second line: graph [compound=true, nodesep="1.0"];
		//$this->getNewLine();
		// ommit third line: node [label="\N"];
		$this->getNewLine();

		$graph = $this->parseGraph();

		fclose($this->parseFileHandle);

		return $graph;
	}
	
	protected function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
		
		// automatical line feed from dot program
		if (!(strpos($this->actualLine, "[") === false) && (strpos($this->actualLine, "]") === false)) {
			$line = substr($this->actualLine, 0, strlen($this->actualLine) - 2);
			
			// retrieve next line
			$nextLine = fgets($this->parseFileHandle);
			$this->actualLine = $line . $nextLine;
		} 
		
		return $this->actualLine;
	}
	
}