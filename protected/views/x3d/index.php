<?php $this->breadcrumbs=array('X3d');?>

<x3d xmlns="http://www.x3dom.org/x3dom" showStat="false" showLog="false" x="0px" y="0px" width="900px" height="600px" altImg="helloX3D-alt.png">
<Scene>
    <Group>
      <?php $this->renderPartial('shapes/basePlattform', $basePlattform); ?>
      
      <?php 
      	foreach ($boxes as $key => $value) {
      		$value[position][x] = $value[position][x] - $basePlattform[size][width] / 2;
      		$value[position][z] = $value[position][z] - $basePlattform[size][length] / 2;
		    $this->renderPartial('shapes/box', $value);
		}
      ?>
      
      <?php 
      	foreach ($edges as $key => $value) {
      		$value[startPos][x] = $value[startPos][x] - $basePlattform[size][width] / 2;
      		$value[startPos][z] = $value[startPos][z] - $basePlattform[size][length] / 2;
      		$value[endPos][x] = $value[endPos][x] - $basePlattform[size][width] / 2;
      		$value[endPos][z] = $value[endPos][z] - $basePlattform[size][length] / 2;
		    $this->renderPartial('shapes/edge', $value);
		}
      ?>
      
    </Group>
    
     <viewpoint position='0 <?php echo $basePlattform[size][width]; ?> 
     					  <?php echo $basePlattform[size][length] / 1.2;?>' 
     			orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>