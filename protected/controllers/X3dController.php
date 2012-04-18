<?php

class X3dController extends Controller
{
	private $dotfile = '/Users/stefan/Sites/3dArch/x3d/subgraph.dot';
	private $outputfile = '/Users/stefan/Sites/3dArch/x3d/subgraph.adot';
	
	public function actionIndex()
	{
		// get layout and parse result --------------------
		$result = Yii::app()->dotLayout->layout($this->dotfile, $this->outputfile);
		$graph =  Yii::app()->dotParser->parse($this->outputfile);
		
		// map to x3d -------------------------------------
		$x3dContent = array();
		
		$x3dContent['main'] = $this->adjustGraphToX3d($graph, true);
		
		foreach ($graph['subgraph'] as $key => $graph) {
			array_push($x3dContent, $this->adjustGraphToX3d($graph, false));
		}
		
		$this->render('index', array(content=>$x3dContent));
	}
	
	private function adjustGraphToX3d($graph, $isMainGraph) { 
		$width = $graph['bb'][2] - $graph['bb'][0];
		$length = $graph['bb'][3] - $graph['bb'][1];
		
		if ($isMainGraph) {
			$colour = array('r'=>1, 'g'=>1, 'b'=>1);
			$height = 0.1;
		} else {
			$colour = array('r'=>0, 'g'=>0.5, 'b'=>1);
			$height = 1;
		}
		// Bounding Box
		$result['bb'] = array(
					'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$length),
					'colour'=>$colour,
					'position'=>array('x' => $graph['bb'][0], 
								  	  'y' => 0, 
								      'z' => $graph['bb'][1]),
		);
		
		// Nodes
		$result['nodes'] = array();
		foreach ($graph['nodes'] as $key => $value) {
			$result['nodes'][$key] = array(
				'name'=>$key,
				'size'=>array('width'=>5, 'height'=>5, 'length'=>5),
				'position'=>array('x' => $value['pos'][0], 
								  'y' => 0, 
								  'z' => $value['pos'][1]),
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
		}
		
		// Edges
		$result['edges'] = array();
		foreach ($graph['edges'] as $key => $value) {
			$result['edges'][$key] = array(
				'startPos'=>array('x' => $value['pos'][1][0], 
								  'y' => 0, 
								  'z' => $value['pos'][1][1]),
				'endPos'=>array('x' => $value['pos'][0][1], 
								'y' => 0, 
								'z' => $value['pos'][0][2]),
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
		}
		
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