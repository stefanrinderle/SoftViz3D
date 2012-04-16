<?php

class X3dController extends Controller
{
	public function actionIndex()
	{
		$x3dContent = array(
			'basePlattform'=>array(
					'size'=>array('width'=>20, 'height'=>0.1, 'length'=>20),
					'position'=>array('x'=>0, 'y'=>0, 'z'=>0),
					'colour'=>array('r'=>0, 'g'=>0.5, 'b'=>1)
			),
			'boxes'=>array(
				'box1'=>array(
					'size'=>array('width'=>0.2, 'height'=>0.2, 'length'=>0.2),
					'position'=>array('x'=>-3.5, 'y'=>0, 'z'=>-3.5),
					'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
					),
				'box2'=>array(
					'size'=>array('width'=>0.2, 'height'=>0.2, 'length'=>0.2),
					'position'=>array('x'=>3.5, 'y'=>0, 'z'=>3.5),
					'colour'=>array('r'=>0, 'g'=>0, 'b'=>1)
					)
			),
			'edges'=>array(
				'edge1'=>array(
					'startPos'=>array('x'=>-3.5, 'y'=>0, 'z'=>-3.5),
					'endPos'=>array('x'=>3.5, 'y'=>0, 'z'=>3.5),
					'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
					),
				'edge2'=>array(
					'startPos'=>array('x'=>0, 'y'=>0, 'z'=>0),
					'endPos'=>array('x'=>-2, 'y'=>0, 'z'=>2),
					'colour'=>array('r'=>0, 'g'=>1, 'b'=>0)
					)
			)
		);
		
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
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}