<?php 

//TODO: duplicated in edgeEnd.php
$coneHeight = 3;
	
if (count($sections) > 3) {
	// start point to first section
	$this->renderPartial('shapes/edgeSection', array('startPos'=>$startPos, 
												 'endPos'=>$sections[0]));
	
	for ($i = 1; $i < count($sections); $i++) {
		$this->renderPartial('shapes/edgeSection', array('startPos'=>$sections[$i - 1], 
												 		 'endPos'=>$sections[$i]));
	}
	
	$this->renderPartial('shapes/edgeEnd', array('startPos'=>$sections[count($sections) - 1], 
												 'endPos'=>$endPos));
	
} else {
	$this->renderPartial('shapes/edgeEnd', array('startPos'=>$startPos, 
												 'endPos'=>$endPos));
}

?>