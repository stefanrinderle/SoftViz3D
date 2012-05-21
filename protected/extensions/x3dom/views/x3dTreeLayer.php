<Transform translation='<?php echo $translation[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
<Group>
<?php

$this->render('baseObjects/basePlattform', $graph->bb);

foreach ($graph->nodes as $key => $value) {
	$this->render('baseObjects/box', $value);
}

?>
</Group>
</Transform>