<?php 

//TODO duplicated in edgeEnd.php
$cylinderRadius = 1;

$edgeVektor = Yii::app()->vectorCalculator->vector($startPos, $endPos);

$height = Yii::app()->vectorCalculator->magnitude($edgeVektor);
	
$rotation = Yii::app()->vectorCalculator->rotationXAxis($edgeVektor);

?>

<Group>
	<Transform translation='<?php echo $startPos[x] . " " . $startPos[y] . " " . $startPos[z] ?>'>
	      <Group>
	          <Transform translation='0 0 0' rotation="0 0 1 -1.57">
	          <Transform translation='0 0 0' rotation="1 0 0 <?php echo $rotation; ?>">
	            <Transform translation='0 <?php echo $height / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	  	              <Material diffuseColor='1 0 0'/>
	                </Appearance>
	                <Cylinder height='<?php echo $height; ?>' radius="<?php echo $cylinderRadius; ?>"></Cylinder>
	            </Shape>
	            </Transform>
	          </Transform>
	          </Transform>
	      </Group>
      </Transform>
    </Group>