<?php

$this->render('/baseObjects/2dRectangle', 
	array(
		'size'=>$bb[size],
		'position'=>array('x' => $bb[position][x] + $bb[size][width] / 2, 
						  'y' => $bb[position][y], 
						  'z' => $bb[position][z] + $bb[size][length] / 2),
		'colour'=>$bb[colour],
		'transparency'=>$bb[transparency],
		'id'=>$id
		)
);

?>