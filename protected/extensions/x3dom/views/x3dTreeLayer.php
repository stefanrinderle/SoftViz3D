<Transform id="ernst" translation='<?php echo $translation[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
<Group>
<?php

$this->render('baseObjects/basePlattform', $graph->bb);

foreach ($graph->nodes as $key => $value) {
	if ($value['isLeaf']) {
		$this->render('baseObjects/leaf', $value);
	}
}

?>
</Group>
</Transform>