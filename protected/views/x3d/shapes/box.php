<Transform translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'>
	<Shape>
    	<Appearance>
          	<Material diffuseColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b]; ?>'/>
        </Appearance>
        
        <Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
	
	</Shape>
</Transform>