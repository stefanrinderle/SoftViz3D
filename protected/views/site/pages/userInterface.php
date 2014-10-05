<?php
$this->pageTitle=Yii::app()->name . ' - Thesis';
$this->breadcrumbs=array(
	'About the user interface',
);
?>

<h1>About the user interface</h1>

<p>
The visualization is available in a browser without requiring a plugin. 
X3DOM, which is used as presentation framework, provides six methods 
to navigate through the 3D scene: Examine, Walk, Fly, Look around, 
Look at and Game. The user is able to switch easily between these 
types and a short overview of the controls of each view will be presented. 
</p>

<h4>Selectable objects</h4>
<p>Each object in the visualization can be selected by a mouse click and will change its 
color as a response for the user.</p>

<h4>Information</h4>
<p>All available information about the selected object will be presented to the user in an 
information section next to the visualization. The main information includes static data 
like name or metric names and values. Furthermore, information about the parent layer 
and all children objects, including their current state (visible / invisible), will be available.
</p>

<h4>Browsing</h4>
<p>All objects which are shown in the information section provide a link. This 
link will simulate a mouse selection of the object which leads to an update of 
the visualization (selection) and an update of the information section. It enables 
the user to browse through the structure and provides a fast way to focus on the 
structure of interest.
</p>

<h4>Expand and hide layers</h4>
<p>It is possible to hide or show layers on demand by following links in the 
information section. Hiding a layer will also hide all children objects and 
all layers below in the hierarchical structure. Showing a layer provides two 
options: Either only the selected layer or the selected layer and all layers 
below will be visible.
</p>

<h4>
Go for some live examples: 
<?php 
	echo CHtml::link(' here', array('/site/page', 'view'=>'examples'));
?>
</h4>
<h4>Please feel free to give Feedback
	<?php 
	echo CHtml::link(' here', array('/site/feedback'));
	?>
</h4> 