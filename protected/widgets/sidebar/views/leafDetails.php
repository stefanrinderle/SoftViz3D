<div>
	<h3><?php echo $leaf->label; ?></h3>
	Parent layer: 
	<?php echo CHtml::link($parentLayer->label, "#", 
					array("onclick" => "showLayerDetailsById(" . $parentLayer->id . ")")); ?>
	
	<br /><br />
					
	<?php 
	if ($parentLayer->isVisible) {
		echo CHtml::button("Remove parent layer", array("onclick" => "leafRemoveLayerById(" . $parentLayer->id . ", " . $leaf->id . ")"));
	} else {
		echo CHtml::button("Show parent layer", array("onclick" => "leafShowLayerById(" . $parentLayer->id . ", " . $leaf->id . ")"));
	}
	?>
</div>