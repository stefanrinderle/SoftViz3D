<div>
	<h3><?php echo $layer->label; ?></h3>
	Parent layer:
	<?php 
	if ($parentLayer) {
		$parentBox = BoxElement::model()->findByAttributes(array('inputTreeElementId' => $parentLayer->id));
		
		echo CHtml::link($parentLayer->label, "#",
				array("onclick" => "showLayerDetailsById(" . $parentBox->id . ")"));
		
		echo "<br /><br />";
	}

	$box = BoxElement::model()->findByAttributes(array('inputTreeElementId' => $layer->id));
	if ($box->isVisible) {
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
				if ($child->type == 1) {
					array_push($leafArray, $child);	
				} else if ($child->type == 0) {
					array_push($layerArray, $child);
				}
			}
		}
	
		if (count($children)) {
			echo "Children:<br />";
			foreach ($layerArray as $child) {
				$box = BoxElement::model()->findByAttributes(array('inputTreeElementId' => $child->id));
				
				echo CHtml::link($child->label, "#", array("onclick" => "showLayerDetailsById(" . $box->id . ")"));
				echo " - ";
				
				if ($box && $box->isVisible) {
					echo CHtml::button("hide", array("onclick" => "layerRemoveLayerById(" . $child->id . ", " . $child->parentId . ")"));
				} else {
					echo CHtml::button("show", array("onclick" => "layerShowLayerById(" . $child->id . ")"));
				}
				echo "<br />";
			}
			foreach ($leafArray as $child) {
				$box = BoxElement::model()->findByAttributes(array('inputTreeElementId' => $child->id));
				
				echo CHtml::link($child->label, "#", array("onclick" => "showLeafDetailsById(" . $box->id . ")"));
				echo " Metric: " . $child->metric1 . " - " . $child->metric2 . "";
				echo "<br />";
			}
		}
	?>
	
</div>