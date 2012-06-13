<div>
	<h3><?php echo $layer->label; ?></h3>
	Parent layer: 
	<?php echo CHtml::link($parentLayer->label, "#", 
					array("onclick" => "showLayerDetailsById(" . $parentLayer->id . ")")); ?>
	<br /><br />
	
	<?php 
	if ($layer->isVisible) {
		echo CHtml::button("Hide layer", array("onclick" => "layerRemoveLayerById(" . $layer->id . ", " . $layer->parent_id . ")"));
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
					echo CHtml::link($child->label, "#", array("onclick" => "showLeafDetailsById(" . $child->id . ")"));
					echo " (SL: " . $child->width . " - H: " . $child->height . ")";
				} else {
					echo CHtml::link($child->label, "#", array("onclick" => "showLayerDetailsById(" . $child->id . ")"));
					echo " - ";
					if ($child->isVisible) {
						echo CHtml::button("hide", array("onclick" => "layerRemoveLayerById(" . $child->id . ", " . $child->parent_id . ")"));
					} else {
						echo CHtml::button("show", array("onclick" => "layerShowLayerById(" . $child->id . ")"));
					}
				}
				echo "<br />";
			}
		}
	?>
	
</div>