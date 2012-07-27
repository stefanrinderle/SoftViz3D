<div>
	<h3><?php echo $layer->label; ?></h3>
	Parent layer:
	<?php 
	if ($parentLayer) {
		echo CHtml::link($parentLayer->label, "#",
				array("onclick" => "showLayerDetailsById(" . $parentLayer->id . ")"));
		
		echo "<br /><br />";
	}

	if ($layer->isVisible) {
		echo CHtml::button("Hide layer", array("onclick" => "layerRemoveLayerById(" . $layer->id . ", " . $layer->parentId . ")"));
	} else {
		echo CHtml::button("Show layer", array("onclick" => "layerShowLayerById(" . $layer->id . ")"));
	}
	
	echo CHtml::button("Expand all", array("onclick" => "layerExpandAllById(" . $layer->id . ")"));
	?>
	
	<br /><br />
	
	<?php 
		$leafArray = array();
		$layerArray = array();
		
		if (count($children)) {
			foreach ($children as $child) {
				if ($child->isLeaf) {
					array_push($leafArray, $child);	
				} else {
					array_push($layerArray, $child);
				}
			}
		}
	
		if (count($children)) {
			echo "Children:<br />";
			foreach ($layerArray as $child) {
				echo CHtml::link($child->label, "#", array("onclick" => "showLayerDetailsById(" . $child->id . ")"));
				echo " - ";
				if ($child->isVisible) {
					echo CHtml::button("hide", array("onclick" => "layerRemoveLayerById(" . $child->id . ", " . $child->parentId . ")"));
				} else {
					echo CHtml::button("show", array("onclick" => "layerShowLayerById(" . $child->id . ")"));
				}
				echo "<br />";
			}
			foreach ($leafArray as $child) {
				echo CHtml::link($child->label, "#", array("onclick" => "showLeafDetailsById(" . $child->id . ")"));
				echo " (M1: " . $child->metric1 . " - M2: " . $child->metric2 . ")";
				echo "<br />";
			}
		}
	?>
	
</div>