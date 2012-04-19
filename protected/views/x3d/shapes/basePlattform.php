<?php

$this->renderPartial('shapes/box', 
	array(
		'size'=>$size,
		// adjust to start at 0,0,0
		'position'=>array('x' => $position[x] + $size[width] / 2, 
						  'y' => 0, 
						  'z' => $position[z] + $size[length] / 2),
		'colour'=>$colour,
		'transparency'=>$transparency
		)
);

?>