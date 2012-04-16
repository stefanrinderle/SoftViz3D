<?php $this->breadcrumbs=array('X3d');?>

<x3d xmlns="http://www.x3dom.org/x3dom" showStat="false" showLog="false" x="0px" y="0px" width="900px" height="600px" altImg="helloX3D-alt.png">
<Scene>
    <Group>
      <?php $this->renderPartial('shapes/box', $basePlattform); ?>
      
      <?php 
      	foreach ($boxes as $key => $value) {
		    $this->renderPartial('shapes/box', $value);
		}
      ?>
      
      <?php 
      	foreach ($edges as $key => $value) {
		    $this->renderPartial('shapes/edge', $value);
		}
      ?>
      
    </Group>
  </Scene>
</x3d>
