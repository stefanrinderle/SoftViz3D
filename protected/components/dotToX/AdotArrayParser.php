<?php

/**
 * Parse an adot file given as array to a graph structure.
 */
class AdotArrayParser extends AdotParser
{
	
	private $adotTextArray;
	private $counter = 0;
	
	public function parse($adotTextArray)
	{
		$this->counter = 0;
		$this->adotTextArray = $adotTextArray;
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
		$this->actualLine = $this->adotTextArray[$this->counter];
		
		$this->checkLineFeed();
		
		$this->counter = $this->counter + 1;
		
		return $this->actualLine;
	}
	
}