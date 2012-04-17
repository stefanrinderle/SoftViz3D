<?php

class X3dController extends Controller
{
	private $dotfile = '/Users/stefan/Sites/3dArch/x3d/simpleGraph2D.dot';
	private $outputfile = '/Users/stefan/Sites/3dArch/x3d/simpleGraph2D.adot';
	
	public function actionIndex()
	{
		// get layout and parse result --------------------
		$result = Yii::app()->dotLayout->layout($this->dotfile, $this->outputfile);
		$graph =  Yii::app()->dotParser->parse($this->outputfile);
		
		// map to x3d -------------------------------------
		$x3dContent = array();
		
		// Bounding Box
		$x3dContent['bb'] = array(
					'size'=>array('width'=>$graph['bb'][2], 'height'=>0.1, 'length'=>$graph['bb'][3]),
					'colour'=>array('r'=>0, 'g'=>0.5, 'b'=>1)
		);
		
		// Nodes
		$x3dContent['nodes'] = array();
		foreach ($graph['nodes'] as $key => $value) {
			$x3dContent['nodes'][$key] = array(
				'name'=>$key,
				'size'=>array('width'=>5, 'height'=>5, 'length'=>5),
				'position'=>array('x' => $value['pos'][0] - $x3dContent['bb'][size][width] / 2, 
								  'y' => 0, 
								  'z' => $value['pos'][1] - $x3dContent['bb'][size][length] / 2),
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
		}
		
		// Edges
		$x3dContent['edges'] = array();
		foreach ($graph['edges'] as $key => $value) {
			$x3dContent['edges'][$key] = array(
				'startPos'=>array('x' => $value['pos'][1][0] - $x3dContent['bb'][size][width] / 2, 
								  'y' => 0, 
								  'z' => $value['pos'][1][1] - $x3dContent['bb'][size][length] / 2),
				'endPos'=>array('x' => $value['pos'][0][1] - $x3dContent['bb'][size][width] / 2, 
								'y' => 0, 
								'z' => $value['pos'][0][2] - $x3dContent['bb'][size][length] / 2),
				'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
			);
		}
		
		$this->render('index', $x3dContent);
	}
	
	/*
	 * public function hexToRGB ($hexColor)
	{
		if( preg_match( '/^#?([a-h0-9]{2})([a-h0-9]{2})([a-h0-9]{2})$/i', $hexColor, $matches ) )
		{
			return array(
            'red' => hexdec( $matches[ 1 ] ),
            'green' => hexdec( $matches[ 2 ] ),
            'blue' => hexdec( $matches[ 3 ] )
			);
		}
		else
		{
			return array( 0, 0, 0 );
		}
	}
	 */
}