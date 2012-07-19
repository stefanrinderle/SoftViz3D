<Group>
	<Transform translation='<?php echo $endSection['startPos'][x] . " " . $endSection['startPos'][y] . " " . $endSection['startPos'][z] ?>'>
	      <Group>
	          <Transform translation='0 0 0' rotation="0 0 1 -1.57">
	          <Transform translation='0 0 0' rotation="1 0 0 <?php echo $endSection['rotation']; ?>">
	            <Transform translation='0 <?php echo $endSection['cylinderLength'] / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	  	              <Material diffuseColor='<?php echo $colour[r] . " " . $colour[g] . " " . $colour[b] ?>'/>
	                </Appearance>
	                <Cylinder height='<?php echo $endSection['cylinderLength']; ?>' radius="<?php echo $lineWidth; ?>"></Cylinder>
	            </Shape>
	            </Transform>
	            <Transform translation='0 <?php echo $endSection['cylinderLength'] + $endSection['coneLength'] / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	                	<Material diffuseColor='1 0 0'/>
	                </Appearance>
	                <Cone bottomRadius='<?php echo $endSection['coneRadius']; ?>' height='<?php echo $endSection['coneLength']; ?>'/>
	            </Shape>
	            </Transform>
	          </Transform>
	          </Transform>
	      </Group>
      </Transform>
    </Group>