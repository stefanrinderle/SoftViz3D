<?php 

$this->breadcrumbs=array('X3d');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);

?>

<span id="views">
	<input type="radio" id="examine" name="repeat" checked="checked" /><label for="examine">Examine</label>
	<input type="radio" id="walk" name="repeat" /><label for="walk">Walk</label>
	<input type="radio" id="fly" name="repeat" /><label for="fly">Fly</label>
	<input type="radio" id="lookAt" name="repeat" /><label for="lookAt">Look at</label>
	<input type="radio" id="lookAround" name="repeat" /><label for="lookAround">Look around</label>
	<input type="radio" id="game" name="repeat" /><label for="game">Game</label>
</span>
<script type="text/javascript">
<!--
$("#views").buttonset().click(function(event) {
	var target = $(event.target).attr('id');

	if (target) {
		setRuntime(target, 'the_x3delement');
	}
});

function setRuntime(typename, id) {
	var configure = document.getElementById(id);
	
	switch (typename)
	{
		case "walk": configure.runtime.walk(); break;
		case "fly": configure.runtime.fly(); break;
		case "examine": configure.runtime.examine(); break;
		case "lookAround": configure.runtime.lookAround(); break;
		case "lookAt": configure.runtime.lookAt(); break;
		case "game": configure.runtime.game(); break;
		
		case "resetView": configure.runtime.resetView(); break;
		case "uprightView": configure.runtime.uprightView(); break;
		case "showAll": configure.runtime.showAll(); break;
		case "nextView": configure.runtime.nextView(); break;
		case "prevView": configure.runtime.prevView(); break;
		case "upSpeed": setSpeed("up", id); break;
		case "downSpeed": setSpeed("down", id); break
		default: configure.runtime.examine();
	}
}

//-->
</script>

<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom" x="0px" y="0px" width="900px" height="600px">
<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="false" ></param>
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
     <viewpoint position='0 300 400' orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>
