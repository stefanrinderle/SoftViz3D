<div>
	Leaf: <?php echo $leaf->label; ?> <br />
	Parent layer: 
	<?php echo CHtml::link($parentLayer->label, "#", 
					array("onclick" => "showLayerInformationById(" . $parentLayer->id . ")")); ?>
					
	<?php 
	echo CHtml::button("Remove parent layer", array("onclick" => "removeLayerById(" . $parentLayer->id . ")")); 

	echo CHtml::button("Show parent layer", array("onclick" => "showLayerById(" . $parentLayer->id . ")"));
	?>
</div>