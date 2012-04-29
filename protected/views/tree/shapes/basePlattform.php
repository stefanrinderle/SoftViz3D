<?php

$this->renderPartial('shapes/box', 
	array(
		'size'=>$size,
		'position'=>array('x' => $position[x] + $size[width] / 2, 
						  'y' => $position[y], 
						  'z' => $position[z] + $size[length] / 2),
		'colour'=>$colour,
		'transparency'=>$transparency,
		'shininess'=>0.9	
		)
);

?>