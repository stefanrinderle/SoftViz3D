<Transform translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'
		onclick="nodeClickedEvent(event);">
	<Shape>
		<Appearance USE="basicNodeAppearence1"></Appearance>
		<Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
	</Shape>
</Transform>