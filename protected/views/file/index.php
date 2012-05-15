<h2>Thats the actual loaded dot file:</h2>

<p><?php echo $filename; ?></p>

<p>
<?php 

foreach ($fileContent as $value) {
	echo $value . "<br />";
}

?>