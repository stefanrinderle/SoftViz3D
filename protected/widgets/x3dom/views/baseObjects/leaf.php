<Transform id='<?php echo $id; ?>' translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='1 0 0' transparency='0' />
			</Appearance>
			<Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
		</Shape>
</Transform>