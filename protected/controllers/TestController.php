<?php

Yii::import('application.vendors.*');
require_once('Image_GraphViz_Copy.php');

class TestController extends Controller
{
	private $depth = array();
	
	private $graphViz;
	
	private $currentPackageName;
	private $parentPackageName;
	
	private $file = "/Users/stefan/Sites/3dArch/protected/data/summary.xml";
	
	private $outputFile = "/Users/stefan/Sites/3dArch/protected/data/test.dot";
	
	public function actionIndex() {
		$this->graphViz = new Image_GraphViz_Copy();
		
		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, "TestController::startElement", "TestController::endElement");
		if (!($fp = fopen($this->file, "r"))) {
			die("could not open XML input");
		}
		
		while ($data = fread($fp, 4096)) {
			if (!xml_parse($xml_parser, $data, feof($fp))) {
				die(sprintf("XML error: %s at line %d",
						xml_error_string(xml_get_error_code($xml_parser)),
						xml_get_current_line_number($xml_parser)));
			}
		}
		xml_parser_free($xml_parser);
		
		$this->graphViz->saveParsedGraph($this->outputFile);
		
		$this->render('index', array(test => "blablabla"));
	}
	
	function startElement($parser, $name, $attrs) {
		for ($i = 0; $i < $this->depth[$parser]; $i++) {
			echo "  ";
		}
		
		if ($name == "PACKAGE") {
			$this->_addSubgraph($attrs[NAME]);
			$this->currentPackageName = $attrs[NAME];
		} else if ($name == "CLASS") {
			$this->_addNode($attrs[NAME], $this->currentPackageName);
		}
		
		$this->depth[$parser]++;
	}
	
	function endElement($parser, $name) {
		$this->depth[$parser]--;
	}
	
	private function _addNode($label, $parentId = 'default') {
		$label = str_replace(array("-", "\\"), "_", $label);
		$parentId = str_replace(array("-", "\\"), "_", $parentId);
		
		$this->graphViz->addNode($label, array(), $parentId);
	}
	
	private function _addSubgraph($label, $parentId = 'default') {
		$label = str_replace(array("-", "\\"), "_", $label);
		
		//TODO: cluster or subgraph
		//void addSubgraph( string $id, array $title, [array $attributes = array()], [string $group = 'default'])
		$this->graphViz->addCluster($label, $label, array(), $parentId);
	}
	
}
