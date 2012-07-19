<Transform id='<?php echo $id; ?>' onclick="edgeClickedEvent(event);">
	<?php 

	foreach ($edgeSections as $key => $section) {
		$this->render('baseObjects/edgeSection', 
				array('section'=>$section,
					  'colour'=>$colour,
					  'lineWidth' => $lineWidth));
	}
	
	$this->render('baseObjects/edgeEnd', 
				array('endSection'=>$endSection,
					  'colour'=>$colour,
					  'lineWidth' => $lineWidth));

	?>
</Transform>