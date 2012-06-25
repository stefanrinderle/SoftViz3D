<Transform id='<?php echo $id; ?>' translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b] ?>' transparency='<?php echo $transparency ?>' />
			</Appearance>
			<Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
		</Shape>
</Transform>