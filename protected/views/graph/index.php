<?php 
$this->pageTitle=Yii::app()->name . ' - Graph';

$this->breadcrumbs=array(
		'Tree', 'Index'
);

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);

	$this->widget('ext.x3dom.EX3domWidget',array(
			'tree'=> $tree, 'type' => 'graph'
	));
?>

<div class="span-5 last" style="float: right;">
	<div id="sidebar">
		<h3>Navigation</h3>
		<span id="views">
			<input type="radio" id="examine" name="repeat" checked="checked" /><label for="examine">Examine</label>
			<input type="radio" id="walk" name="repeat" /><label for="walk">Walk</label>
			<input type="radio" id="fly" name="repeat" /><label for="fly">Fly</label>
			<input type="radio" id="lookAround" name="repeat" /><label for="lookAround">Look around</label>
			<input type="radio" id="lookAt" name="repeat" /><label for="lookAt">Look at</label>
			<input type="radio" id="game" name="repeat" /><label for="game">Game</label>
		</span>
		<br />
		<span id="speed">
			Navigation Speed: <span id="speedValue">1</span>
			<span id="increase">+</span>
			<span id="decrease">-</span>
		</span>
		<span id="viewpoint">
			Viewpoint: <span id="reset">Reset</span>
		</span>
		<br />
	</div><!-- sidebar -->
</div>

<script type="text/javascript">
<!--

$("#views").buttonset().click(function(event) {
	var target = $(event.target).attr('id');

	if (target) {
		setRuntime(target, 'the_x3delement');
	}
});

$("#viewpoint #reset").button().click(function() {
	var configure = document.getElementById('the_x3delement');
	configure.runtime.resetView();
	}
);

$("#speed #decrease").button().click(function() {
	var configure = document.getElementById('the_x3delement');
	$('#speedValue').text(configure.runtime.speed(configure.runtime.speed() - 1));
	}
);

$("#speed #increase").button().click(function() {
	var configure = document.getElementById('the_x3delement');
	$('#speedValue').text(configure.runtime.speed(configure.runtime.speed() + 1));
	}
);

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