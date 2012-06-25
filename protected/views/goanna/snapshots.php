<?php
$this->pageTitle=Yii::app()->name . ' - Goanna viewer';
$this->breadcrumbs=array(
	'Goanna project snapshots',
);
?>

<h2>Snapshots - Project "<?php echo $project[name]; ?>"</h2>

<table>
<tr><th>Name</th><th>Date</th><th>Number of files</th><th>Warnings total</th><th>Warnings supressed</th><th>Import Warnings</th><th>Import Dependencies</th></tr>

<?php 

$first = true;
foreach($project[snapshots] as $snapshot) {
	echo "<tr><td>";
	echo $snapshot[name];
	echo "</td><td>";
	echo $snapshot[timestamp];
	echo "</td><td>";
	echo $snapshot[num_files];
	echo "</td><td>";
	echo $snapshot[total];
	echo "</td><td>";
	echo $snapshot[supressed];
	echo "</td><td>";
	echo CHtml::link("Import", array('goanna/importSnapshotWarnings', 'projectId'=>$project[id], 'snapshotId'=>$snapshot[id]));
	if ($first) {
		echo "</td><td>";
		echo CHtml::link("Import", array('goanna/importSnapshotDependencies', 'projectId'=>$project[id], 'snapshotId'=>$snapshot[id]));
		$first = false;
	}
	echo "</td></tr>";
}

?>

</table>