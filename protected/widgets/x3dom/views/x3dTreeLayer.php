<Transform translation='<?php echo $graph->bb['translation']['x']. " " . $graph->bb['translation']['y'] . " " . $graph->bb['translation']['z']; ?>'>
<Group>
<?php

$this->render('baseObjects/basePlattform', array('bb' => $graph->bb, 'id' => $graph->id));

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