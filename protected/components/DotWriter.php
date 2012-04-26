<?php

class DotWriter extends CApplicationComponent
{

	public function write($elements, $outputFile) {
		$this->createDotFile($elements, $outputFile);
	}

	private function createDotFile($array, $outputFile) {
		$lines = $this->createDotFileLines($array);

		$this->write_data($lines, $outputFile);
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

	private function write_data($data, $fname) {
		$fp = fopen($fname, "w");

		foreach ($data as $key => $value) {
			fwrite($fp, "$value\n");
		}

		fclose($fp);
	}

}