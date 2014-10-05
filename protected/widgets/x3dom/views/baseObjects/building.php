<Transform  id='<?php echo $element->id; ?>' 
			translation='<?php echo $element->translation; ?>'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='<?php echo $element->color; ?>' 
						  transparency='<?php echo $element->transparency; ?>' />
			</Appearance>
			<Box size='<?php echo $element->size; ?>'/>
		</Shape>
</Transform>