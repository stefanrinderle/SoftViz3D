<?php

class X3dController extends Controller
{
	private $dotfile = '/Users/stefan/Sites/3dArch/x3d/parser.dot';
	private $outputfile = '/Users/stefan/Sites/3dArch/x3d/parser.adot';
	
	private $subgraphLevel = 0;
	
	public function actionIndex()
	{
		// get layout and parse result --------------------
		$result = Yii::app()->dotLayout->layout($this->dotfile, $this->outputfile);
		$graph =  Yii::app()->dotFileParser->parse($this->outputfile);
		
		// map to x3d -------------------------------------
		$x3dContent = array();
		
		$x3dContent['main'] = $this->adjustGraphToX3d($graph);
		
		$x3dContent = array_merge($x3dContent, $this->adjustSubGraphToX3d($graph['subgraph']));
		
		$translation = array('x'=> (- ($x3dContent['main']['bb']['size']['width'] / 2)),
							 'y'=> 0,
							 'z'=> (- ($x3dContent['main']['bb']['size']['length'] / 2)));
		
		$this->render('index', array(translation=>$translation, content=>$x3dContent));
	}
	
	private function adjustSubGraphToX3d($subgraph, $result=array()) {
		$this->subgraphLevel++;
		foreach ($subgraph as $key => $value) {
			array_push($result, $this->adjustGraphToX3d($value));
			
			if (count($value['subgraph'])) {
				$result = $this->adjustSubGraphToX3d($value['subgraph'], $result);
			}
		}
		$this->subgraphLevel--;
		return $result;
	}
	
	private function adjustGraphToX3d($graph) {
		$result = array(); 
		// Bounding Box
		$result['bb'] = $this->adjustBb($graph['bb']);
		
		// Nodes
		$result['nodes'] = array();
		foreach ($graph['nodes'] as $key => $value) {
			$result['nodes'][$key] = $this->adjustNode($key, $value);
		}
		
		// Edges
		$result['edges'] = array();
		foreach ($graph['edges'] as $key => $value) {
			$result['edges'][$key] = $this->adustEdge($value);
		}
		
		return $result;
	} 
	
	private function adjustBb($bb) {
		$width = $bb[2] - $bb[0];
		$length = $bb[3] - $bb[1];
		
		if ($this->subgraphLevel == 0) {
			$colour = array('r'=>0, 'g'=>0, 'b'=>1);
			$height = 0.1;
			$transpareny = 0.9;
		} else {
			$colourValue = $this->subgraphLevel * 0.3;
			$colour = array('r'=>0, 'g'=>$colourValue, 'b'=>0);
			$height = 1;
			$transpareny = 0.9 - $this->subgraphLevel * 0.1;
		}
		
		$result = array(
					'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$length),
					'colour'=>$colour,
					'position'=>array('x' => $bb[0], 
								  	  'y' => 0, 
								      'z' => $bb[1]),
					'transparency'=>$transpareny
		);
		
		return $result;
	}
	
	private function adjustNode($name, $node) {
		$result = array(
				'name'=>$name,
				'size'=>array('width'=>5, 'height'=>5, 'length'=>5),
				'position'=>array('x' => $node['pos'][0], 
								  'y' => $node['z'], 
								  'z' => $node['pos'][1]),
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0),
				'transparency'=>0
			);
			
		return $result;
	}
	
	private function adustEdge($edge) {
			// convert edge section points
			$sections = array();
			for ($i = 2; $i < count($edge['pos']); $i++) {
				$section = array('x' => $edge['pos'][$i][0], 
								 'y' => 0, 
								 'z' => $edge['pos'][$i][1]);
				
				array_push($sections, $section);
			}			
			
			$result = array(
				'startPos'=>array('x' => $edge['pos'][1][0], 
								  'y' => 0, 
								  'z' => $edge['pos'][1][1]),
				'endPos'=>array('x' => $edge['pos'][0][1], 
								'y' => 0, 
								'z' => $edge['pos'][0][2]),
				'sections'=>$sections,
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
			
			return $result;
	}
	
	public function actionDb() {
		$connection=Yii::app()->db;
		
		$sql="SELECT * FROM functions";
		
		$command=$connection->createCommand($sql);
		$rows=$command->queryAll(); 
		
	 	$this->render('db', array(rows=>$rows));
	}
}