<Group>
	<Transform id='<?php echo $edge->id; ?>' translation='<?php echo $edge->translation; ?>' onclick="newEdgeClickedEvent(event);">
		<?php 
	
		foreach ($edge->sections as $key => $section) {
			
			if ($section->type == EdgeSectionElement::$TYPE_DEFAULT) {
				
				$this->render('baseObjects/edgeSection',
						array('section'=>$section,
							  'color' => $edge->color,
							  'lineWidth' => $edge->lineWidth));
				
			} else if ($section->type == EdgeSectionElement::$TYPE_END) {
				
				$this->render('baseObjects/edgeEnd',
						array('section' => $section,
							  'color' => $edge->color,
							  'lineWidth' => $edge->lineWidth));
			}
		}
		?>
	</Transform>
</Group>