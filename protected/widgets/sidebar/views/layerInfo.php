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
	
	<br />
	<?php 
	if ($layer->isVisible) {
		echo CHtml::button("Remove layer", array("onclick" => "removeLayerById(" . $layer->id . ", 'layer')"));
	} else {
		echo CHtml::button("Show layer", array("onclick" => "showLayerById(" . $layer->id . ", 'layer')"));
	}
	?>
	
	<br />
	<?php 
	if ($currentDepth > 0) {
		//echo CHtml::button("Remove an layer", array("onclick" => "removeLayerByDepth(" . $currentDepth . ")"));
	}
	
	?>
	
	<br />
	<?php echo $currentDepth; ?>
	<br />
	<?php echo $maxDepth; ?>
</div>