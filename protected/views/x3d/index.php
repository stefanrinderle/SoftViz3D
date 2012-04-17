<?php $this->breadcrumbs=array('X3d');?>

<x3d xmlns="http://www.x3dom.org/x3dom" showStat="false" showLog="false" x="0px" y="0px" width="900px" height="600px" altImg="helloX3D-alt.png">
<Scene>
    <Group>
      <?php $this->renderPartial('shapes/basePlattform', $bb); ?>
      
      <?php 
      	foreach ($nodes as $key => $value) {
		    $this->renderPartial('shapes/box', $value);
		}
      ?>
      
      <?php 
      	foreach ($edges as $key => $value) {
		    $this->renderPartial('shapes/edge', $value);
		}
      ?>
      
    </Group>
    
     <viewpoint position='0 <?php echo $bb[size][width]; ?> 
     					  <?php echo $bb[size][length] / 1.2;?>' 
     			orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>