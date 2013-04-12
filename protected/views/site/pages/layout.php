<?php
$this->pageTitle=Yii::app()->name . ' - Thesis';
$this->breadcrumbs=array(
	'About the layout',
);
?>

<h1>About the layout module</h1>

<p>
The 3D layout is built as a module and could be used by other visualizations. 
It is based on layers representing the districts mentioned above. A layer is the 
representation of a composite node and consists of the children nodes and 
dependencies of the structure as illustrated by the rectangles in the figure below. 
The layout for each layer is calculated by a replaceable two dimensional layout 
algorithm and will then be translated into 3D positions and objects.
</p>

<table><tr>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/layeredLayout.jpg" width="300px"></td>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/layeredLayout3d.jpg" width="200px"></td>
</tr></table>

<h2>Layout for the structure view</h2>
<p>
In order to reserve the space for all children districts, the size of all underlying layers 
must be knwon. The solution is a post-order traversal of the objects in the input tree 
according to the node identifiers. The size of every node is calculated and then given 
as the input for the parent layer. The size of the leaf nodes depend on their numeric 
attributes. The footprint size is the size of the layer it represents and will prevent 
overlappings. The overall height of a layer above the base platform is depending on 
the depth of the composite node in the input tree.
</p>

<h2>Layout for the dependency view</h2>

<p>
The first step towards the layout of the dependency view is the translation of the input 
dependencies. As a result, a new type of node can exist in the input tree (interface node) 
and any arrow represents an edge between siblings (flat dependency edge). Each arrow has 
a start and end position. In case of a curved arrow, multiple section points within the 
curve are given. Further, the number of dependencies is available in the edge and interface class.
</p>

<p>
The calculation sequence for the dependency view is the same as in the structure view 
(post-order tree traversal). The leaf node size is depending on the number of in- and 
out-going flat edges. The size of interface nodes and the diameter of the arrows 
depend on the number of edges they represent. The footprint node size depends on the size 
of its children layer.
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