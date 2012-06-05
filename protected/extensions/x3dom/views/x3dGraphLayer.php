<Transform translation='<?php echo $translation[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
<Group>
<?php

$this->render('baseObjects/basePlattform', $graph->bb);

foreach ($graph->nodes as $key => $value) {
	if ($value['isLeaf']) {
		$this->render('baseObjects/leaf', $value);
	} else {
		$this->render('baseObjects/node', $value);
	}
}

foreach ($graph->edges as $key => $value) {
	$this->render('baseObjects/edge', $value);
}

?>
</Group>
</Transform>