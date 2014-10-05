<Transform id="<?php echo $element->id; ?>" onclick="layerClickedEvent(event);" 
			translation="<?php echo $element->translation; ?>" rotation="1 0 0 -1.57">
		<Shape>
	    	<Appearance>
	          	<Material diffuseColor='<?php echo $element->color; ?>' 
	          				transparency='<?php echo $element->transparency; ?>' />
	        </Appearance>
	        
	        <Rectangle2D size='<?php echo $element->size ?>' solid='false'/>
		</Shape>
</Transform>