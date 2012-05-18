<?php
$this->pageTitle=Yii::app()->name . ' - Tree';
$this->breadcrumbs=array(
		'Tree',
);
$this->breadcrumbs=array(
	'Tree', 'Index'
);

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);

function generateX3DOM($node, $self, $transX, $transZ) {
		$nodeWidth = $node->x3dInfos->bb[size][width];
		$nodeLength = $node->x3dInfos->bb[size][length];
		
		// get translation of parent
		$translation[x] = $transX;
		$translation[y] = $node->x3dInfos->bb[position][y];
		$translation[z] = $transZ;
		
		if (!$node->level == 0) {
			$translation[x] = $translation[x] - $nodeWidth / 2;
			$translation[z] = $translation[z] - $nodeLength / 2;
		} 
		
		$self->renderPartial('//x3dom/x3dGroup', array(graph=>$node->x3dInfos, translation=>$translation));
		
		// calculate values for the children nodes
		$content = $node->content;
		
		foreach ($content as $key => $value) {
			$label = trim($value->label); 
			
			// layout node position
			$nodePositionX = $node->x3dInfos->nodes[$label][position][x];
			$nodePositionZ = $node->x3dInfos->nodes[$label][position][z];
			
			if (!$node->level == 0) {
				$nodePositionX = $nodePositionX + ($transX - ($nodeWidth / 2));
				$nodePositionZ = $nodePositionZ + ($transZ - ($nodeLength / 2));
			} 
			//TODO: Why is this required?
			if ($value->x3dInfos) {
				generateX3DOM($value, $self, $nodePositionX, $nodePositionZ);
			}
		}
}

?>

<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom" x="0px" y="0px" width="900px" height="600px">
<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="false" ></param>
	
	<Transform translation='<?php echo - $tree->x3dInfos->bb[size][width] / 2 . " 0 " . - $tree->x3dInfos->bb[size][length] / 2; ?>'>
	
	<?php generateX3DOM($tree, $this, 0, 0); ?>
	
	</Transform>
	
     <viewpoint position='0 300 400' orientation='1 0 0 -0.78'></viewpoint>
  </Scene>
</x3d>