<?php

/**
 * Parse an adot file given as array to a graph structure.
 */
class AdotArrayParser extends AdotParser
{
	
	private $dotTextArray;
	private $counter = 0;
	
	public function parse($dotTextArray)
	{
		$this->counter = 0;
		$this->dotTextArray = $dotTextArray;
		// ommit first line: digraph G {
		$this->getNewLine();
		// ommit second line: graph [compound=true, nodesep="1.0"];
		//$this->getNewLine();
		// ommit third line: node [label="\N"];
		$this->getNewLine();

		$graph = $this->parseGraph();

		return $graph;
	}
	
	protected function getNewLine() {
		$this->actualLine = $this->dotTextArray[$this->counter];
		
		// automatical line feed from dot program
		if (!(strpos($this->actualLine, "[") === false) && (strpos($this->actualLine, "]") === false)) {
			$line = substr($this->actualLine, 0, strlen($this->actualLine) - 2);
			
			// retrieve next line
			$nextLine = fgets($this->parseFileHandle);
			$this->actualLine = $line . $nextLine;
		} 
		
		$this->counter = $this->counter + 1;
		
		return $this->actualLine;
	}
	
}