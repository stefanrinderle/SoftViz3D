<?php
$this->breadcrumbs=array(
	'Tree', 'Index'
);

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);

function preorder($node, $self, $transX, $transZ) {
	if ($node instanceof Node) {
		
		$self->renderPartial('x3dGroup', array(graph=>$node->x3d, 
											   translation=>array(x=>$transX, y=>0, z=>$transZ),
											   depth=>$node->depth,
											   main=>$node->main));
		
		foreach ($node->content as $key => $value) {
			preorder($value, $self, $node->x3d[nodes][$value->label][position][x], $node->x3d[nodes][$value->label][position][z]); 
		}
	}
}

?>

<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom" x="0px" y="0px" width="900px" height="600px">
<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="false" ></param>
	
	<Transform translation='<?php echo - $tree->x3d[bb][size][width] / 2 . " 0 " . - $tree->x3d[bb][size][length] / 2; ?>'>
	
	<?php preorder($tree, $this, 0, 0); ?>
	
	</Transform>
	
     <viewpoint position='0 300 400' orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>



