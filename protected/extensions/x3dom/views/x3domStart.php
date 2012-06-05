<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom" x="0px" y="0px" width="700px" height="400px">
<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="false" ></param>

	<?php $x3dInfos = $tree->getX3dInfos(); ?>
	<Transform translation='<?php echo - $x3dInfos->bb[size][width] / 2 . " 0 " . - $x3dInfos->bb[size][length] / 2; ?>'>
	
	<Group>
		<Shape DEF="basicLeaf">
			<Appearance>
				<Material emissiveColor='1 0 0' diffuseColor='1 0 0' transparency='0' />
			</Appearance>
			<Box size='36 10 36'/>
		</Shape>
		
		<!-- LOD forceTransitions="true" range="800, 1000" DEF="basicLeaf">
			<Group DEF="LOD_0">
				<Shape>
			    	<Appearance>
			          	<Material emissiveColor='0 1 0' diffuseColor='0 1 0' transparency='0.3' />
			        </Appearance>
			        <Box size='36 10 36'/>
				</Shape>
			</Group> 
			<Group DEF="LOD_1">
				<Shape>
			    	<Appearance>
			          	<Material emissiveColor='0 1 0' diffuseColor='0 1 0' transparency='0.8' />
			        </Appearance>
			        <Box size='36 10 36'/>
				</Shape>
			</Group> 
			<Group DEF="LOD_2">
				<Shape>
			    	<Appearance>
			          	<Material emissiveColor='0 1 0' diffuseColor='0 1 0' transparency='1' />
			        </Appearance>
			        <Box size='36 10 36'/>
				</Shape>
			</Group>
		</LOD> -->
		
		<Shape DEF="basicNode">
	    	<Appearance DEF="basicNodeAppearence0">
	          	<Material transparency='1' />
	        </Appearance>
	        <Appearance DEF="basicNodeAppearence1">
	          	<Material transparency='0.3' />
	        </Appearance>
	        <Appearance DEF="basicNodeAppearence2">
	          	<Material transparency='1' />
	        </Appearance>
	        <Box size='1 1 1'/>
		</Shape>
	</Group>
	
	