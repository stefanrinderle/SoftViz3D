<Transform id='<?php echo $element->id; ?>' 
		   translation='<?php echo $element->translation; ?>'
		   onclick="footprintClickedEvent(event);">
	<Shape>
		<Appearance>
				<Material transparency='<?php echo $element->transparency; ?>' />
		</Appearance>
		<Box size='<?php echo $element->size; ?>'/>
	</Shape>
</Transform>