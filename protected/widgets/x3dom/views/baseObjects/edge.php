<Transform id='<?php echo $id; ?>' onclick="edgeClickedEvent(event);">
<?php 

if (count($sections)) {
	// start point to first section
	$this->render('baseObjects/edgeSection', array('startPos'=>$startPos, 
												 'endPos'=>$sections[0],
												 'lineWidth' => $lineWidth));
	
	for ($i = 1; $i < count($sections); $i++) {
		if ($sections[$i - 1] != $sections[$i]) {
			$this->render('baseObjects/edgeSection', array('startPos'=>$sections[$i - 1], 
													 		 'endPos'=>$sections[$i],
												 			'lineWidth' => $lineWidth));
		}
	}
	
	$this->render('baseObjects/edgeEnd', array('startPos'=>$sections[count($sections) - 1], 
												'endPos'=>$endPos,
												//'rotation' => $rotation,
												'lineWidth' => $lineWidth));
	
} else {
	$this->render('baseObjects/edgeEnd', array('startPos' => $startPos, 
												'endPos' => $endPos,
												//'rotation' => $rotation,
												'lineWidth' => $lineWidth));
}

?>
</Transform>