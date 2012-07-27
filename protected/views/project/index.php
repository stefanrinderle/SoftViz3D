<?php
$this->pageTitle=Yii::app()->name . ' - Project viewer';
$this->breadcrumbs=array(
	'Project overview',
);
?>

<h2>Project list</h2>

<?php if(Yii::app()->user->hasFlash('success')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>
<?php endif; ?>


<?php 
echo CHtml::button('New project', array('submit' => array('project/new')));
?>

<table>
<tr><th>Name</th><th>File uploaded</th><th>Structure view</th>
	<th>Dependency View <br />(Detail mode)</th><th>Dependency View <br />(Metric mode)</th><th>Options</th></tr>

<?php 

if (count($projects) == 0) {
	echo "Could not load any projects...";
} else {
	foreach($projects as $project) {

		$layouts = $project->layouts;
		$layoutType = array();
		foreach ($layouts as $layout) {
			$layoutType[$layout->type] = $layout;
		}
		
		echo "<tr><td>";
		echo "(" . $project->id . ")" . $project->name;
		echo "</td><td>";
		
		$time = $project->getFileUpdateTime();
		if ($time != -1) {
			echo CHtml::link('Show/edit file', array('file/index', 'projectId' => $project->id));
			echo "<br />";
			echo $time;
		} else {
			echo "no file";
		}

		echo "</td><td>";
		
		if (array_key_exists(Layout::$TYPE_STRUCTURE, $layoutType)) {
			echo "view <br />";
			echo CHtml::link('recalculate', array('view/index', 'projectId' => $project->id, 'viewType' => Layout::$TYPE_STRUCTURE));
		} else if ($time != -1) {
			echo CHtml::link('create layout', array('view/index', 'projectId' => $project->id, 'viewType' => Layout::$TYPE_STRUCTURE));
		} else {
			echo "";
		}
		
		echo "</td><td>";
		
		if (array_key_exists(Layout::$TYPE_DEPENDENCY_DETAIL, $layoutType)) {
			echo $layoutType[Layout::$TYPE_DEPENDENCY_DETAIL]->id;
		} else if ($time != -1) {
			echo "create";
		} else {
			echo "";
		}
		
		echo "</td><td>";
		
		if (array_key_exists(Layout::$TYPE_DEPENDENCY_METRIC, $layoutType)) {
			echo $layoutType[Layout::$TYPE_DEPENDENCY_METRIC]->id;
		} else if ($time != -1) {
			echo "create";
		} else {
			echo "";
		}
		
		echo "</td><td>";
		
		echo CHtml::link('Import/Upload file', array('import/index', 'projectId' => $project->id));
		
		echo "</td></tr>";
	}
}


?>

</table>