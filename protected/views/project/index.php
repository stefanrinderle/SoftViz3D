<?php
$this->pageTitle=Yii::app()->name . ' - Project viewer';
$this->breadcrumbs=array(
	'Project overview',
);
?>

<h2>Project list</h2>

<table>
<tr><th>Name</th><th>File uploaded</th><th>Structure view</th>
	<th>Dependency View <br />(Detail mode)</th><th>Dependency View <br />(Metric mode)</th></tr>

<?php 

if (count($projects) == 0) {
	echo "Could not load any projects...";
} else {
	foreach($projects as $project) {
		echo "<tr><td>";
		echo "(" . $project->id . ")" . $project->name;
		echo "</td><td>";
		
		$time = $project->getFileUpdateTime();
		if ($time != -1) {
			echo $time;
		} else {
			echo "no file";
		}

		echo "</td><td>";
		//$latest = array_pop($project['snapshots']);
		//echo $latest['num_files'];
		echo "</td><td>";
		
		echo "</td><td>";
		//echo $latest['total'];
		echo "</td></tr>";
	}
}

?>

</table>