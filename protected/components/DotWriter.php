<?php

class DotWriter extends CApplicationComponent
{

	public function writeToArray($elements) {
		return $this->createDotFileLines($elements);
	}
	
	public function writeToFile($elements, $outputFile) {
		$lines = $this->createDotFileLines($elements);

		$this->writeFile($lines, $outputFile);
	}

	private function createDotFileLines($elements) {
		$result = array();

		array_push($result, 'digraph G {');

		foreach ($elements as $key => $value) {
			$elementString = str_replace(".", "_", $value->name);
			$elementString .= '[shape="rectangle" width="' . $value->size->width . '", height="' . $value->size->height . '", fixedsize=true, type="' . $value->type . '"]';
			$elementString .= ';';
			
			array_push($result, $elementString);
		}

		array_push($result, '}');

		return $result;
	}

	private function writeFile($data, $fname) {
		$fp = fopen($fname, "w");

		foreach ($data as $key => $value) {
			fwrite($fp, "$value\n");
		}

		fclose($fp);
	}
	
}