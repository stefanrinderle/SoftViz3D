<?php
$this->pageTitle=Yii::app()->name . ' - Goanna viewer';
$this->breadcrumbs=array(
	'Goanna overview',
);
?>

<h2>Goanna project list</h2>

<table>
<tr><th>Name</th><th>Number of snapshots</th></tr>

<?php 

foreach($projects as $project) {
	echo "<tr><td>";
	echo CHtml::link($project[name], array('goanna/snapshots', 'id'=>$project[id]));
	echo "</td><td>";
	echo count($project[snapshots]);
	echo "</td></tr>";
}

?>

</table>