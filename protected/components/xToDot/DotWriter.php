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
			if ($value instanceOf GraphComponent) {
				$elementString = str_replace(".", "_", $value->label);
				$elementString .= '[shape="rectangle" width="' . $value->size[width] . '", height="' . $value->size[height] . '", fixedsize=true,';
				if ($value instanceof Leaf) {
					$elementString .= ' type="leaf"]';
				} else if ($value instanceof Node) {
					$elementString .= ' type="node"]';
				}
			} else if ($value instanceof Edge) {
				$elementString = str_replace(".", "_", $value->in) . " -> " . str_replace(".", "_", $value->out);
			}
			
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