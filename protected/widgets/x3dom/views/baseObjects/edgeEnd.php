<Group>
	<Transform translation='<?php echo $section->translation; ?>'>
	      <Group>
	          <Transform translation='0 0 0' rotation="0 0 1 -1.57">
	          <Transform translation='0 0 0' rotation="1 0 0 <?php echo $section->rotation; ?>">
	            <Transform translation='0 <?php echo $section->cylinderLength / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	  	              <Material diffuseColor='<?php echo $color; ?>'/>
	                </Appearance>
	                <Cylinder height='<?php echo $section->cylinderLength; ?>' radius="<?php echo $lineWidth; ?>"></Cylinder>
	            </Shape>
	            </Transform>
	            <Transform translation='0 <?php echo $section->cylinderLength + $section->coneLength / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	                	<Material diffuseColor='<?php echo $color; ?>'/>
	                </Appearance>
	                <Cone bottomRadius='<?php echo $section->coneRadius; ?>' height='<?php echo $section->coneLength; ?>'/>
	            </Shape>
	            </Transform>
	          </Transform>
	          </Transform>
	      </Group>
      </Transform>
    </Group>