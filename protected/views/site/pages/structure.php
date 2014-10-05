<?php
$this->pageTitle=Yii::app()->name . ' - Thesis';
$this->breadcrumbs=array(
	'About structure',
);
?>

<h1>About structure</h1>
<p>The structure view provides a visualization for the hierarchical structure of the
input project. It should be easy to recognize the dierent hierarchy levels and allow
an exploration of the structure. Additionally it is possible to provide numerical
attributes (e.g. software metrics) to differentiate between the base components.
The input for the visualization is given in a tree format as follows:
</p>
<ul>
	<li>Root node:<br />
There is exactly one root node in a tree. It is a special node and therefore able
to handle general visualization parameters.</li>
	<li>Composite nodes<br />
A composite node in a tree has at least one child and exactly one parent node.
It has no additional visualization information except from the structure in
which it is located.</li>
	<li>Leaf nodes:<br />
A leaf node has no children nodes and exactly one parent node. It is able to
handle special numerical attributes which will be used for the representation
in the visualization.</li>
</ul>

<table><tr>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/structure_dot.jpg" width="300px"></td>
<td>
<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom">

<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="false" ></param>

	<Transform id="x3dSceneWrapper"><Transform id="80238" onclick="layerClickedEvent(event);" 
			translation="-20 5 -14" rotation="1 0 0 -1.57">
		<Shape>
	    	<Appearance>
	          	<Material diffuseColor='0.87 1 1' 
	          				transparency='0' />
	        </Appearance>
	        
	        <Rectangle2D size='33 9' solid='false'/>
		</Shape>
</Transform><Transform  id='80239' 
			translation='-32 7.999988 -14'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='1 0.55 0' 
						  transparency='0' />
			</Appearance>
			<Box size='5.999976 5.999976 5.999976'/>
		</Shape>
</Transform><Transform  id='80240' 
			translation='-8 7.999988 -14'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='1 0.55 0' 
						  transparency='0' />
			</Appearance>
			<Box size='5.999976 5.999976 5.999976'/>
		</Shape>
</Transform><Transform id="80241" onclick="layerClickedEvent(event);" 
			translation="20 5 14" rotation="1 0 0 -1.57">
		<Shape>
	    	<Appearance>
	          	<Material diffuseColor='0.87 1 1' 
	          				transparency='0' />
	        </Appearance>
	        
	        <Rectangle2D size='33 9' solid='false'/>
		</Shape>
</Transform><Transform  id='80242' 
			translation='8 7.999988 14'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='1 0.55 0' 
						  transparency='0' />
			</Appearance>
			<Box size='5.999976 5.999976 5.999976'/>
		</Shape>
</Transform><Transform  id='80243' 
			translation='32 7.999988 14'
			onclick="leafClickedEvent(event);">
		<Shape>
			<Appearance>
				<Material diffuseColor='1 0.55 0' 
						  transparency='0' />
			</Appearance>
			<Box size='5.999976 5.999976 5.999976'/>
		</Shape>
</Transform><Transform id="80244" onclick="layerClickedEvent(event);" 
			translation="0 0 0" rotation="1 0 0 -1.57">
		<Shape>
	    	<Appearance>
	          	<Material diffuseColor='1.17 1.3 1' 
	          				transparency='0' />
	        </Appearance>
	        
	        <Rectangle2D size='75 39' solid='false'/>
		</Shape>
</Transform><Transform id='80245' 
		   translation='-20 0 -14'
		   onclick="footprintClickedEvent(event);">
	<Shape>
		<Appearance>
				<Material transparency='0' />
		</Appearance>
		<Box size='0 0 0'/>
	</Shape>
</Transform><Transform id='80246' 
		   translation='20 0 14'
		   onclick="footprintClickedEvent(event);">
	<Shape>
		<Appearance>
				<Material transparency='0' />
		</Appearance>
		<Box size='0 0 0'/>
	</Shape>
</Transform>	</Transform>
     <viewpoint position='0 300 400' orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>

</td>
</tr></table>

<p>
The composite
nodes are depicted as districts and leaf nodes as buildings. The visualization on the right hand side shows
the structure view of the tree next to it. The number of numerical input attributes,
i.e. metrics, for the leaf nodes (building) is restricted to a maximum of two. This
prevents a cognitive overload and opens up more possibilities for the visualization.
The side length and height of the buildings are dependent on the two numerical
attributes of the leaf nodes (3, 5, 6, 7). To get a better overview of the hierarchy,
each district is built on top of the parent object so the result looks like a city built
on hills (2, 4). The space for the children districts will therefore be reserved in the
parent layer to prevent any overlappings (1).
</p>

<h4>
Go for some live examples: 
<?php 
	echo CHtml::link(' here', array('/site/page', 'view'=>'examples'));
?>
</h4>