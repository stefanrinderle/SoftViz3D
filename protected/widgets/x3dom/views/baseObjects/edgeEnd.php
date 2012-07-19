<?php 

$cylinderRadius = $lineWidth;
$coneRadius = $cylinderRadius * 2;
$coneHeight = $cylinderRadius * 3;

$edgeVektor = VectorCalculator::vector($startPos, $endPos);

$mainEdgeHeight = VectorCalculator::magnitude($edgeVektor);
$cylinderHeight = $mainEdgeHeight - $coneHeight;

$rotation = VectorCalculator::rotationXAxis($edgeVektor);
?>

<Group>
	<Transform translation='<?php echo $startPos[x] . " " . $startPos[y] . " " . $startPos[z] ?>'>
	      <Group>
	          <Transform translation='0 0 0' rotation="0 0 1 -1.57">
	          <Transform translation='0 0 0' rotation="1 0 0 <?php echo $rotation; ?>">
	            <Transform translation='0 <?php echo $cylinderHeight / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	  	              <Material diffuseColor='1 0 0'/>
	                </Appearance>
	                <Cylinder height='<?php echo $cylinderHeight; ?>' radius="<?php echo $cylinderRadius; ?>"></Cylinder>
	            </Shape>
	            </Transform>
	            <Transform translation='0 <?php echo $cylinderHeight + $coneHeight / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	                	<Material diffuseColor='1 0 0'/>
	                </Appearance>
	                <Cone bottomRadius='<?php echo $coneRadius; ?>' height='<?php echo $coneHeight; ?>'/>
	            </Shape>
	            </Transform>
	          </Transform>
	          </Transform>
	      </Group>
      </Transform>
    </Group>