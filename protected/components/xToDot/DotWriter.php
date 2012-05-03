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
			if ($value instanceOf TreeElement) {
				$elementString = str_replace(".", "_", $value->label);
				$elementString .= ' [shape="rectangle" width="' . $value->size[width] . '", height="' . $value->size[height] . '", fixedsize=true';
				
				$content = TreeElement::model()->findAllByAttributes(array('parent_id'=>$value->id));
				if (count($content) > 0) {
					$elementString .= ', type="node"';
				} else {
					$elementString .= ', type="leaf"';
				}
				$elementString.= "]";
			} else if ($value instanceOf EdgeElement) {
				$elementString = str_replace(".", "_", $value->inElement->label) . " -> " . str_replace(".", "_", $value->outElement->label);
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