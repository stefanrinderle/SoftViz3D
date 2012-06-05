<?php
$this->pageTitle=Yii::app()->name . ' - Tree';

$this->breadcrumbs=array(
	'Tree', 'Index'
);

$this->widget('ext.x3dom.EX3domWidget',array(
	'tree'=> $tree, 'type' => 'tree'
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