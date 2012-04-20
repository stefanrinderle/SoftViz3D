<?php
$this->breadcrumbs=array(
	'Graph Viz',
);?>

<p>Datei: <?php echo $input; ?></p>

<?php 
$this->renderPartial('../dumpArray', array('dumpArray'=>$graph));
?>