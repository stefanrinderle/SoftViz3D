<?php

class GraphVizController extends Controller
{
	private $dotfile = '/Users/stefan/Sites/3dArch/protected/views/graphViz/simpleGraph.dot';
	private $outputfile = '/Users/stefan/Sites/3dArch/protected/views/graphViz/simpleGraph.adot';

	private $parseFileHandle;
	private $actualLine;
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		
		if ($this->createAdotFile()) {
			$graph = $this->parseAdotFile();
			
			$this->render('index', array(graph=>$graph));
		}
	}

	private function getNewLine() {
		$this->actualLine = fgets($this->parseFileHandle);
		Yii::log($this->actualLine, 'error', 'parser');
	}
	
	private function parseAdotFile() {
		$this->parseFileHandle = fopen($this->outputfile, "r");

		
		// ommit first line: digraph G {
		$this->getNewLine();
		// ommit second line: node [label="\N"];
		$this->getNewLine();

		// retrieve boundbox rectangle: graph [bb="0,0,62,108"]; --> 0,0,62,108
		$this->getNewLine();
		$bb = $this->retrieveParam($this->actualLine, 'bb');
		
		$this->getNewLine();
		$nodes = $this->retrieveNodes();
		
		$edges = $this->retrieveEdges($file_handle);

		fclose($this->parseFileHandle);

		$graph = array('bb'=>$bb, 'nodes'=>$nodes, 'edges'=>$edges);
		
		return $graph;
	}

	private function retrieveEdges() {
		$edges = array();

		$line = $this->actualLine;
		
		//Yii::log($this->actualLine, 'error', 'parser');
		
		while (!$this->isEnd($line)) {
			array_push($edges, $this->retrieveParam($line, 'pos'));
			
			$this->getNewLine();
			$line = $this->actualLine;
		}
		
		return $edges;
	}

	private function retrieveNodes() {
		$nodes = array();

		$line = $this->actualLine;
		
		while (!($this->isEdge($line) || $this->isEnd($line))) {
			array_push($nodes, $this->retrieveParam($line, 'pos'));
			
			$this->getNewLine();
			$line = $this->actualLine;
		}
		
		return $nodes;
	}

	private function isEdge($line) {
		return (!(strpos($line, "->") === false));
	}
	
	private function isEnd($line) {
		if ($this->actualLine) {
			return (!(strpos($line, "}") === false));
		} else {
			return false;
		}
	}

	private function retrieveParam($line, $param) {
		$result = "";
		// get the beginning of the param
		$start = strpos($line, $param);
		// ommit = and "
		$result = substr($line, $start + strlen($param) + 2);

		$end = strpos($result, '"');
		$result = substr($result, 0, $end);

		return $result;
	}

	private function createAdotFile() {
		// Load source file
		$error = "";
		if (!file_exists($this->dotfile)) {
			$error = "Error loading source dot file: " + $this->dotfile;
		} else {
			// Create adot file
			$command = '/usr/local/bin/dot';
			$command .= ' -o' .escapeshellarg($this->outputfile)
			.' '  .escapeshellarg($this->dotfile)
			.' 2>&1';

			exec($command, $msg, $return_val);

			if ($return_val != 0) {
				$error = "failure creating adot file: " + $msg;
			}
		}

		if (!empty($error)) {
			$this->render('error', array(error=>$error));
			return false;
		} else {
			return true;
		}
	}
}