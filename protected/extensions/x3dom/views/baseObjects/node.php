<Transform translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'>
	<Shape>
		<Appearance USE="basicNodeAppearence1"></Appearance>
		<Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
	</Shape>
</Transform>


<!-- Transform translation='<?php echo $position[x] . " " . $position[y] . " " . $position[z]; ?>'>
	<LOD forceTransitions="true" range="200, 3000">
			<Group DEF="LOD_0">
				<Shape>
			    	<Appearance USE="basicNodeAppearence0"></Appearance>
			        <Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
				</Shape>
			</Group> 
			<Group DEF="LOD_1">
				<Shape>
		    		<Appearance USE="basicNodeAppearence1"></Appearance>
			        <Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
				</Shape>
			</Group> 
			<Group DEF="LOD_2">
				<Shape>
		    		<Appearance USE="basicNodeAppearence2"></Appearance>
			        <Box size='<?php echo $size[width] . " " . $size[height] . " " . $size[length]; ?>'/>
				</Shape>	
			</Group>
		</LOD>
</Transform  -->