<div>
	<h3><?php echo $layer->label; ?></h3>
	Parent layer: 
	<?php echo CHtml::link($parentLayer->label, "#", 
					array("onclick" => "showLayerDetailsById(" . $parentLayer->id . ")")); ?>
	
	<br /><br />
	
	<?php 
	if ($layer->isVisible) {
		echo CHtml::button("Remove layer", array("onclick" => "layerRemoveLayerById(" . $layer->id . ")"));
	} else {
		echo CHtml::button("Show layer", array("onclick" => "layerShowLayerById(" . $layer->id . ")"));
	}
	
	echo CHtml::button("Expand all", array("onclick" => "layerExpandAllById(" . $layer->id . ")"));
	?>
	
	<br /><br />
	
	<?php 
		if (count($children)) {
			echo "Children:<br />";
			foreach ($children as $child) {
				if ($child->isLeaf) {
					echo "Leaf: " . CHtml::link($child->label, "#", array("onclick" => "showLeafDetailsById(" . $child->id . ")"));
				} else {
					echo "Layer: " . CHtml::link($child->label, "#", array("onclick" => "showLayerDetailsById(" . $child->id . ")"));
				}
				echo "<br />";
			}
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