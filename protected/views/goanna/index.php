<?php
$this->pageTitle=Yii::app()->name . ' - Goanna viewer';
$this->breadcrumbs=array(
	'Goanna overview',
);
?>

<h2>Goanna project list</h2>

<table>
<tr><th>Name</th><th>Number of snapshots</th><th>Number of files</th><th>Latest bug count</th></tr>

<?php 

if (count($projects) == 0) {
	echo "Could not load any projects...";
} else {

	foreach($projects as $project) {
		echo "<tr><td>";
		echo CHtml::link($project['name'], array('goanna/snapshots', 'id'=>$project['id']));
		echo "</td><td>";
		echo count($project['snapshots']);
		echo "</td><td>";
		$latest = array_pop($project['snapshots']);
		echo $latest['num_files'];
		echo "</td><td>";
		echo $latest['total'];
		echo "</td></tr>";
	}
}

?>

</table>