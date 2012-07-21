<?php

class DotArrayParser extends AbstractDotParser {
	
	private $inputArrayCounter = 0;
	private $inputArray;
	
	public function parse($array, $includeEdges = true) {
		$this->inputArray = $array;
		$this->inputArrayCounter = 0;
		
		$this->start($includeEdges);
		
		return $this->result;
	}

	protected function getNewLine() {
		$this->setCurrentLine($this->inputArray[$this->inputArrayCounter++]);
		
		$this->checkLineFeed();
	}
	
}