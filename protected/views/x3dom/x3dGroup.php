<Transform translation='<?php echo $translation[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
<Group>
<?php

$this->renderPartial('//x3dom/baseObjects/basePlattform', $graph->bb);

foreach ($graph->nodes as $key => $value) {
	$this->renderPartial('//x3dom/baseObjects/box', $value);
}

foreach ($graph->edges as $key => $value) {
	$this->renderPartial('//x3dom/baseObjects/edge', $value);
}

?>
</Group>
</Transform>