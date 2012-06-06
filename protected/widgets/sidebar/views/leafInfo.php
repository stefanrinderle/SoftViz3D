<div>
	Leaf: <?php echo $leaf->label; ?> <br />
	Parent layer: 
	<?php echo CHtml::link($parentLayer->label, "#", 
					array("onclick" => "showLayerInformationById(" . $parentLayer->id . ")")); ?>
</div>