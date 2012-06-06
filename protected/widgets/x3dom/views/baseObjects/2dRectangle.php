<Transform id="<?php echo $id; ?>" translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>' rotation="1 0 0 -1.57"
			onclick="showLayerInformation(event);">
		<Shape>
	    	<Appearance>
	          	<Material emissiveColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b]; ?>'
	          			  diffuseColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b]; ?>'
	          			  transparency='<?php echo $transparency; ?>' shininess='<?php echo $shininess; ?>' />
	        </Appearance>
	        
	        <Rectangle2D size='<?php echo $size[width] . " " . $size[length]?>' solid='false'/>
		</Shape>
</Transform>

