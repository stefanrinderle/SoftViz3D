<?php

class X3dController extends Controller
{
	private $dotfile = '/Users/stefan/Sites/3dArch/x3d/world.dot';
	private $outputfile = '/Users/stefan/Sites/3dArch/x3d/world.adot';
	
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
		
		$translation = array('x'=> (- ($x3dContent['main']['bb']['size']['width'] / 2)),
							 'y'=> 0,
							 'z'=> (- ($x3dContent['main']['bb']['size']['length'] / 2)));
		
		$this->render('index', array(translation=>$translation, content=>$x3dContent));
	}
	
	private function adjustGraphToX3d($graph, $isMainGraph) { 
		$width = $graph['bb'][2] - $graph['bb'][0];
		$length = $graph['bb'][3] - $graph['bb'][1];
		
		if ($isMainGraph) {
			$colour = array('r'=>1, 'g'=>1, 'b'=>1);
			$height = 0.1;
			$transpareny = 1;
		} else {
			$colour = array('r'=>0.3, 'g'=>0.3, 'b'=>0.5);
			$height = 1;
			$transpareny = 0.2;
		}
		
		// Bounding Box
		$result['bb'] = array(
					'size'=>array('width'=>$width, 'height'=>$height, 'length'=>$length),
					'colour'=>$colour,
					'position'=>array('x' => $graph['bb'][0], 
								  	  'y' => 0, 
								      'z' => $graph['bb'][1]),
					'transparency'=>$transpareny
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
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0),
				'transparency'=>0
			);
		}
		
		// Edges
		$result['edges'] = array();
		foreach ($graph['edges'] as $key => $value) {
			
			// convert edge section points
			$sections = array();
			for ($i = 2; $i < count($value['pos']); $i++) {
				$section = array('x' => $value['pos'][$i][0], 
								 'y' => 0, 
								 'z' => $value['pos'][$i][1]);
				
				array_push($sections, $section);
			}			
			
			$result['edges'][$key] = array(
				'startPos'=>array('x' => $value['pos'][1][0], 
								  'y' => 0, 
								  'z' => $value['pos'][1][1]),
				'endPos'=>array('x' => $value['pos'][0][1], 
								'y' => 0, 
								'z' => $value['pos'][0][2]),
				'sections'=>$sections,
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