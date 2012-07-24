<Transform id="<?php echo $id; ?>" translation='<?php echo $bb['position']['x'] . " " . $bb['position']['y'] . " " . $bb['position']['z']; ?>' rotation="1 0 0 -1.57"
			onclick="layerClickedEvent(event);">
		<Shape>
	    	<Appearance>
	          	<Material diffuseColor='<?php echo $bb['colour']['r'] . " " . $bb['colour']['g'] . " " . $bb['colour']['b']; ?>'
	          			  transparency='<?php echo $bb['transparency']; ?>' />
	        </Appearance>
	        
	        <Rectangle2D size='<?php echo $bb['size']['width'] . " " . $bb['size']['length']?>' solid='false'/>
		</Shape>
</Transform>