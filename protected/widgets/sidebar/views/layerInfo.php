<div>
	Layer: <?php echo $layer->label; ?> <br />
	Parent layer: 
	<?php echo CHtml::link($parentLayer->label, "#", 
					array("onclick" => "showLayerInformationById(" . $parentLayer->id . ")")); ?>
	
	<?php 
		if (count($children)) {
			echo "<br />Children:<br />";
			foreach ($children as $child) {
				if ($child->isLeaf) {
					echo "Leaf: " . CHtml::link($child->label, "#", array("onclick" => "showLeafInformationById(" . $child->id . ")"));
				} else {
					echo "Layer: " . CHtml::link($child->label, "#", array("onclick" => "showLayerInformationById(" . $child->id . ")"));
				}
				echo "<br />";
			}
		}
	?>
	
</div>