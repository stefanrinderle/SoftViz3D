<?php 

if (!$main) {
	$translation[x] = $translation[x] - $graph[bb][size][width] / 2;
	$translation[z] = $translation[z] - $graph[bb][size][length] / 2;
}

$translation[y] = $depth * 10;

?>

<Transform translation='<?php echo $translation[x] + $modifier[x]. " " . $translation[y] . " " . $translation[z]; ?>'>
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