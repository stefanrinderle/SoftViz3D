<Transform id='<?php echo $graph->id; ?>' depth='<?php echo $graph->depth; ?>'
		   translation='<?php echo $graph->bb[translation][x]. " " . $graph->bb[translation][y] . " " . $graph->bb[translation][z]; ?>'>
<Group>
<?php

$this->render('baseObjects/basePlattform', $graph->bb);

if ($graph->nodes) {
	foreach ($graph->nodes as $key => $value) {
		if ($value['isLeaf']) {
			$this->render('baseObjects/leaf', $value);
		}
	}	
}


?>
</Group>
</Transform>