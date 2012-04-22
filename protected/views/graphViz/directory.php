<?php
$this->breadcrumbs=array(
	'Graph Viz - Directory',
);?>

<h2><?php echo $fileName; ?></h2>

<?php 
foreach ($fileContent as $line) {
    echo $line . "<br />";
}

?>
