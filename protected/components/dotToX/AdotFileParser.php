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
		
		$this->checkLineFeed();
		
		return $this->actualLine;
	}
	
}