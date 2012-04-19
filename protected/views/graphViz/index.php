<?php
$this->breadcrumbs=array(
	'Graph Viz',
);?>

<h1>Einlesen, Layout und parsen von dot-Files</h1>

<p>Datei: <?php echo $input; ?></p>

<?php 
$this->renderPartial('../dumpArray', array('dumpArray'=>$graph));
?>