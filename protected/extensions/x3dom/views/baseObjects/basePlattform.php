<?php

$this->render('/baseObjects/2dRectangle', 
	array(
		'size'=>$size,
		'position'=>array('x' => $position[x] + $size[width] / 2, 
						  'y' => $position[y], 
						  'z' => $position[z] + $size[length] / 2),
		'colour'=>$colour,
		'transparency'=>$transparency
		)
);

?>