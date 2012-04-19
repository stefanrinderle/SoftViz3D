<?php $this->breadcrumbs=array('X3d');?>

<x3d xmlns="http://www.x3dom.org/x3dom" showStat="true" showLog="false" x="0px" y="0px" width="900px" height="600px" altImg="helloX3D-alt.png">
<Scene>
	<Transform translation='<?php echo $translation['x'] . ' ' .  
								  	   $translation['y'] . ' ' .
								       $translation['z']; ?>'>
    <Group>
      <?php 
      
      foreach ($content as $key => $graph) {
      	 
      	$this->renderPartial('shapes/basePlattform', $graph[bb]);

      	foreach ($graph[nodes] as $key => $value) {
      		$this->renderPartial('shapes/box', $value);
      	}

      	foreach ($graph[edges] as $key => $value) {
      		$this->renderPartial('shapes/edge', $value);
      	}

      }
      ?>
    </Group>
    </Transform>
     <viewpoint position='0 300 400' 
     			orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>