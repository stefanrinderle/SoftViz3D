<Transform translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'
			onclick="showLeafInformation(event);">
		<Shape>
			<Appearance>
				<Material DEF="basicLeafAppearence" emissiveColor='1 0 0' diffuseColor='1 0 0' transparency='0' />
			</Appearance>
			<Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
		</Shape>
</Transform>