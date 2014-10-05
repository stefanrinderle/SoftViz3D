<?php
$this->pageTitle=Yii::app()->name . ' - Goanna viewer';
$this->breadcrumbs=array(
	'Goanna project snapshots',
);
?>

<h2>Snapshots - Project "<?php echo $project['name']; ?>"</h2>

<table>
<tr><th>Name</th><th>Date</th><th>Number of files</th><th>Warnings total</th><th>Warnings supressed</th><th>Import Warnings</th></tr>

<?php 

$first = true;
foreach($project['snapshots'] as $snapshot) {
	echo "<tr><td>";
	echo $snapshot['name'];
	echo "</td><td>";
	echo $snapshot['timestamp'];
	echo "</td><td>";
	echo $snapshot['num_files'];
	echo "</td><td>";
	echo $snapshot['total'];
	echo "</td><td>";
	echo $snapshot['suppressed'];
	echo "</td><td>";
	if ($first) {
		echo CHtml::link("Import incl. dep.", array('goanna/importSnapshot', 'projectId'=>$project['id'], 'snapshotId'=>$snapshot['id'], 'importDependencies' => true));
		$first = false;
	} else {
		echo CHtml::link("Import", array('goanna/importSnapshot', 'projectId'=>$project['id'], 'snapshotId'=>$snapshot['id'], 'importDependencies' => false));
	}
	echo "</td></tr>";
}

?>

</table>