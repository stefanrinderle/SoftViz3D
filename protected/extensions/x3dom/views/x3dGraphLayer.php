<Transform translation='<?php echo $translation[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
<Group>
<?php

$this->render('baseObjects/basePlattform', $graph->bb);

foreach ($graph->nodes as $key => $value) {
	$this->render('baseObjects/box', $value);
}

foreach ($graph->edges as $key => $value) {
	$this->render('baseObjects/edge', $value);
}

?>
</Group>
</Transform>