<?php

$this->render('/baseObjects/box', 
	array(
		'size'=>$size,
		'position'=>array('x' => $position[x] + $size[width] / 2, 
						  'y' => $position[y] + $size[height] / 2, 
						  'z' => $position[z] + $size[length] / 2),
		'colour'=>$colour,
		'transparency'=>$transparency,
		'shininess'=>0.9	
		)
);

?>