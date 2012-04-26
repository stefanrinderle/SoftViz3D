<Transform translation='<?php echo $translation[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
<Group>
<?php

$this->renderPartial('shapes/basePlattform', $graph[bb]);

foreach ($graph[nodes] as $key => $value) {
	$this->renderPartial('shapes/box', $value);
}

foreach ($graph[edges] as $key => $value) {
	$this->renderPartial('shapes/edge', $value);
}

?>
</Group>
</Transform>