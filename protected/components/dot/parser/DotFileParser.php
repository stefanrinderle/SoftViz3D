<?php

class DotFileParser extends AbstractDotParser {

	private $parseFileHandle;
	
	public function parse($dotFile, $includeEdges = true) {
		$this->parseFileHandle = fopen($dotFile, "r");
		
		$this->start($includeEdges);
		
		fclose($this->parseFileHandle);
		
		return $this->result;
	}
	
	protected function getNewLine() {
		$this->currentLine = fgets($this->parseFileHandle);
		
		$this->checkLineFeed();
	}
	
}