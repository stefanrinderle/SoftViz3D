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
	<th>Dependency View <br />(Detail mode)</th><th>Dependency View <br />(Metric mode)</th></tr>

<?php 

if (count($projects) == 0) {
	echo "Could not load any projects...";
} else {
	foreach($projects as $project) {

		$layoutArray = $project->getLayoutTypeArray();
		
		echo "<tr><td>";
		echo "(" . $project->id . ")" . $project->name;
		echo "</td><td>";
		
		$time = $project->getFileUpdateTime();
		if ($time != -1) {
			echo CHtml::link('Show/edit file', array('file/index', 'projectId' => $project->id));
			echo "<br />";
			echo $time . "<br />";
			echo CHtml::link('Import/Upload file', array('import/index', 'projectId' => $project->id));
		} else {
			echo "no file <br />";
			echo CHtml::link('Import/Upload file', array('import/index', 'projectId' => $project->id));
		}

		echo "</td><td>";
		
		$this->widget('application.widgets.project.ProjectLayoutCell',
				array('layoutType' => Layout::$TYPE_STRUCTURE, 'layoutArray' => $layoutArray, 'project' => $project));
				
		echo "</td><td>";
		
		$this->widget('application.widgets.project.ProjectLayoutCell',
				array('layoutType' => Layout::$TYPE_DEPENDENCY_DETAIL, 'layoutArray' => $layoutArray, 'project' => $project));
		
		echo "</td><td>";
		
		$this->widget('application.widgets.project.ProjectLayoutCell',
				array('layoutType' => Layout::$TYPE_DEPENDENCY_METRIC, 'layoutArray' => $layoutArray, 'project' => $project));
		
		echo "</td><td>";
	}
}


?>

</table>