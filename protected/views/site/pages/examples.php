<?php
$this->pageTitle=Yii::app()->name . ' - Examples';
$this->breadcrumbs=array(
	'Examples',
);
?>

<p>
The example files are created by the Goanna tool. <a href="http://redlizards.com/">Goanna</a> (http://redlizards.com/) is a
	static program analysis tool for C/C++ software projects based on model
	checking.
</p>

<h2>Structures</h2>
<p>The structure view provides a visualization for the hierarchical structure of the
input project. It should be easy to recognize the different hierarchy levels and allow
an exploration of the structure. Additionally it is possible to provide numerical
attributes (e.g. software metrics) to differentiate between the base components.</p>
<p>The visualization is based on the city metaphor. Packages are depicted as districts 
and classes as buildings. The number of numerical input attributes,
i.e. metrics, for the classes (building) is restricted to a maximum of two. This
prevents a cognitive overload and opens up more possibilities for the visualization.
The side length and height of the buildings are dependent on the two numerical
attributes. To get a better overview of the hierarchy,
each district is built on top of the parent object so the result looks like a city built
on hills. The space for the children districts will therefore be reserved in the
parent layer to prevent any overlappings.</p>

<h4>Visualized metrics</h4>
<p>The following structure view examples are created with the information provided by the
Goanna tool mentioned above. The analysis was performed in June 2012. The building size and
length is dependent on the possible errors found by the static analysis tool.</p>

<h4>
<?php 
echo CHtml::link('MongoDB project (June 2012) structure view', 
	array('x3d/index', 'layoutId' => 1), array('target' => '_blank'));
?>			
</h4>

<h4>
<?php 
echo CHtml::link('Chromium project (June 2012) structure view', 
	array('x3d/index', 'layoutId' => 3), array('target' => '_blank'));
?>			
</h4>
<p>
As you can see in this example. There are a few problems with this size of example:<br />
- It requires a powerful graphic card on the client.
- There are still z-index problems in the visualization.
</p>

<h2>Dependencies</h2>
<p>
The dependency view is focussed on the dependencies within the given structure. It
is not useful to add the dependencies on top of the structure view because the main
goal is different. As a result, the basic 3D layout for the structure is altered. 
Instead of building the underlying districts beyond the base platform, the dependency
view is built downwards. A composite node summarizes the underlying objects and
will therefore be shown on top of the children objects. A children composite
node will be shown as a footprint in the parent layer. Leaf nodes will be
depicted as buildings inside the districts.
</p>

<h4>
<?php 
echo CHtml::link('Simple MVC structure example', 
	array('x3d/index', 'layoutId' => 4), array('target' => '_blank'));
?>			
</h4>

<h4>
<?php 
echo CHtml::link('MongoDB project (June 2012)', 
	array('x3d/index', 'layoutId' => 2), array('target' => '_blank'));
?>			
</h4>

<h4>
<?php 
echo CHtml::link('irssi project (June 2012)', 
	array('x3d/index', 'layoutId' => 5), array('target' => '_blank'));
?>			
</h4>
<h4><a href=""></a></h4>