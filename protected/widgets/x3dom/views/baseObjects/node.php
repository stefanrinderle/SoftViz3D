<Transform id='<?php echo $id; ?>' 
		   translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'
		   onclick="nodeClickedEvent(event);">
	<Shape>
		<Appearance>
				<Material transparency='<?php echo $transparency ?>' />
		</Appearance>
		<Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
	</Shape>
</Transform>