<?php
$this->breadcrumbs=array(
	'Graph Viz - Directory',
);?>

<?php 
$this->renderPartial('../dumpArray', array('dumpArray'=>$directoryStructure));
?>

<br /><br /><br /><br />

<?php 

//foreach ($dotFile as $key => $value) {
//	echo $value . '<br />';
//}

?>