<Transform translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'>
	<Shape>
    	<Appearance>
          	<Material emissiveColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b]; ?>'
          			  diffuseColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b]; ?>'
          			  transparency='<?php echo $transparency; ?>' shininess='<?php echo $shininess; ?>' />
        </Appearance>
        
        <Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
	</Shape>
	<!-- Transform translation="0 <?php echo $size[height] * 2; ?> 0">
	    <Shape>
	    	<Appearance>
				<Material diffuseColor='1 0 0'/>
	        </Appearance>
	        <Text solid='true' string='"<?php echo $name; ?>"'>
	        	<FontStyle family='SERIF' justify='MIDDLE' size="500" />
	        </Text>
	    </Shape>
    </Transform> -->
</Transform>