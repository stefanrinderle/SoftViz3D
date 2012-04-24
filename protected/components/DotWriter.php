<?php

class DotWriter extends CApplicationComponent
{

	public function write($nodes, $outputFile) {
		$this->createDotFile($nodes, $outputFile);
	}

	private function createDotFile($array, $outputFile) {
		$lines = $this->createDotFileLines($array);

		$this->write_data($lines, $outputFile);
	}

	private function createDotFileLines($nodes) {
		$result = array();

		array_push($result, 'digraph G {');

		foreach ($nodes as $key => $value) {
			$nodeString = str_replace(".", "_", $value->name);
			$nodeString .= '[shape="rectangle" width="' . $value->size->width . '", height="' . $value->size->height . '", fixedsize=true]';
			$nodeString .= ';';
			
			array_push($result, $nodeString);
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