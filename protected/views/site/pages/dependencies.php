<?php
$this->pageTitle=Yii::app()->name . ' - Thesis';
$this->breadcrumbs=array(
	'About dependencies',
);
?>

<h1>About dependencies</h1>

<p>
The dependency view is focussed on the dependencies within the given structure. 
It is not useful to add the dependencies on top of the structure view because the 
main goal is different. As a result, the basic 3D layout for the structure is altered. 
Instead of building the underlying districts beyond the base platform, the dependency 
view is built downwards. A composite node summarizes the underlying objects and will 
therefore be shown on top of the children objects (1). A children composite node will 
be shown as a footprint in the parent layer (2, 4). Leaf nodes will be depicted as 
buildings inside the districts (3, 5, 6, 7).
</p>
<p>
<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/simpleGraphExample" width="300px">
</p>

<h2>Input dependency transformation</h2>
<p>
The dependencies are given as relations of nodes based on the structure input tree. 
A dependency has no restrictions and can connect any two nodes. This leads to a dependency 
mesh across the input structure and the number of dependencies is in general at least twice 
as high as the number of nodes. Different approaches depicting the dependencies as they are 
led to an overload of information presented to the user.
</p>
<p>
The proposed solution to the described problem is the break up of the dependency mesh. 
Therefore, the input dependencies need to be analyzed and transformed. The focus here 
is placed on dependencies between nodes with different parent nodes. The idea is to 
identify the path between these nodes in the input tree structure in order to present 
a hierarchical summation to the user.
</p>
<p>
As shown in the figures below, dependencies between nodes with different parent nodes 
(directed edge) will be transformed to the dependency path (dotted edges). Since this 
step leads to even more dependencies, all in- and outgoing edges of each parent node 
will be concentrated and represented by a newly created interface node (Int2 and Int4). 
The total number of connections between two objects in the tree will be given as an 
additional input to the layout algorithm and the visualization.
</p>
<table><tr>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/abstractDepTreeImpl" width="300px"></td>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/abstractDepTreeImplFormal" width="200px"></td>
</tr></table>
<p>
Every dependency created by this transformation is either one between siblings or a 
dependency to or from the parent object. Since all dependencies between nodes with 
different parent nodes are transformed and therefore deleted, any remaining dependency 
can now be assigned one of the following types:
</p>
<ul>
	<li>Flat edge:<br />Edge between siblings in the input structure tree.</li>
	<li>Flat dependency edge:<br />Edge between siblings in the tree, created by the transformation.</li>
	<li>Hierarchical dependency edge:<br />Edge between a composite node and its according interface node.</li>
</ul>
<p>
The break up of the dependency mesh and the fine-grained separation of the resulting 
dependencies eliminate a huge number of problems, e.g. the layout and the representation. 
The visualization can now focus on the proposed disjoint sets of edges after the transformation.
</p>

<h2>Adequate dependency representation</h2>
<p>
The representation of the dependencies is based on the result edge types mentioned above. 
All flat dependency edges are depicted by arrows between the buildings within the district. 
Multiple edges between two objects will be combined and the sum of dependencies will be used 
in the resulting representation. The more dependencies an arrow represents, the thicker 
its representation (diameter) will be.
</p>
<p>
The hierarchical dependency edges are depicted by special buildings which are reminiscent 
of elevator shafts. This building type provides the visual connection between the parent 
and children districts and end in the footprint node representation in the parent district. 
As used for the arrow representation, the more dependencies an elevator building represents, 
the bigger the size (side length) of it will be. The figure below shows the dependency view 
of the input of the figures above.
</p>
<p>
<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/simpleDepGraphExample" width="500px">
</p>
<p>
Using real-life projects instead of example structures can lead to an overload of information 
if all edges would be visible. This depends on the overall project size and the actual number 
of dependencies to represent. Therefore, the dependency view has two different modes:
</p>
<ul>
	<li>Detail mode:<br />The flat edges will be depicted as arrows between the buildings. The first of the two possible input metrics will be used for the side length of the buildings.</li>
	<li>Metric mode:<br />To avoid too many edges within a district, none of the flat edges will depicted as arrows. This information will be represented by the size of the buildings. The more in- and out-going flat edges a building would have, the bigger is its size.</li>
</ul>
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