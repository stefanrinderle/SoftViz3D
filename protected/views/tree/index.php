<?php
$this->pageTitle=Yii::app()->name . ' - Tree';

$this->breadcrumbs=array(
	'Tree', 'Index'
);

$this->widget('application.widgets.x3dom.X3domWidget',array(
	'root' => $root, 'layers' => $layers, 'type' => 'tree'
));
?>

<!-- div class="span-5 last" style="float: right;">
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
			
		</span>
		<span id="viewpoint">
			Viewpoint: <span id="reset">Reset</span>
		</span>
		<br />
		
		<span id="manipulation">
			<span id="remove">Remove</span>
			<span id="add">Add</span>
			<span id="test">Test</span>
		</span>
	</div><!-- sidebar 
</div  -->

<div id="sidebar" style="float: right; width: 250px;">
	<h3><a href="#">Information</a></h3>
	<div id="information">
		Click on an object inside the visualization to show informations about it.
	</div>
	<h3><a href="#">Navigation</a></h3>
	<div id="navigation">
		<p>
			<input type="radio" id="examine" name="repeat" checked="checked" /><label for="examine">Examine</label>
			<input type="radio" id="walk" name="repeat" /><label for="walk">Walk</label>
			<input type="radio" id="fly" name="repeat" /><label for="fly">Fly</label>
			<input type="radio" id="lookAround" name="repeat" /><label for="lookAround">Look around</label>
			<input type="radio" id="lookAt" name="repeat" /><label for="lookAt">Look at</label>
			<input type="radio" id="game" name="repeat" /><label for="game">Game</label>
		</p>
		<p>
		Speed: <span id="speedValue">1</span>
			<span id="increase">+</span>
			<span id="decrease">-</span>
		</p>
		<p><span id="reset">Reset view</span></p>
	</div>
	<h3><a href="#">Manipulation</a></h3>
	<div id="manipulation">
		<span id="remove">Remove</span>
		<span id="add">Add</span>
		<span id="test">Test</span>
	</div>
</div>

<script>
	$(function() {
		$( "#sidebar" ).accordion({
			collapsible: true
		});
	});
</script>