<?php

$this->renderPartial('shapes/box', 
	array(
		'size'=>$size,
		'position'=>array('x'=>0, 'y'=>0, 'z'=>0),
		'colour'=>$colour
		)
);

?>