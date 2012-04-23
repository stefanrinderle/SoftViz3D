<?php
$this->breadcrumbs=array(
	'Graph Viz', 'Index'
);?>

<h1>GraphViz Features</h1>

<h2>Parse a directory structure in dot file</h2>

<p>
<?php echo CHtml::link('directory',array('graphViz/directory')); ?>
</p>

<p>
<?php echo CHtml::link('directoryArray',array('graphViz/directoryArray')); ?>
</p>


<h2>Convert a dot file to adot and parse the generated file to an php array</h2>

<p>
<?php echo CHtml::link('dotToArray',array('graphViz/dotToArray')); ?>
</p>