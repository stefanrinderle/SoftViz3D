<?php

$this->renderPartial('shapes/box', 
	array(
		'size'=>$size,
		// adjust to start at 0,0,0
		'position'=>array('x' => $position[x] + $size[width] / 2, 
						  'y' => $position[y], 
						  'z' => $position[z] + $size[length] / 2),
		'colour'=>$colour,
		'transparency'=>$transparency
		)
);

?>