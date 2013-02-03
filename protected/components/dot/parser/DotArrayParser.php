<?php

class DotArrayParser extends AbstractDotParser {
	
	private $inputArrayCounter = 0;
	private $inputArray;
	
	public function parse($array) {
		$this->inputArray = $array;
		$this->inputArrayCounter = 0;
		
		$this->start();
		
		return $this->result;
	}

	protected function getNewLine() {
		if ($this->inputArrayCounter < count($this->inputArray)) {
			$this->currentLine = $this->inputArray[$this->inputArrayCounter++];
			$this->checkLineFeed();
		} else {
			$this->inputArrayCounter++;
			return null;
		}
	}
	
}