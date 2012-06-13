<div>
	<h3><?php echo $leaf->label; ?></h3>
	<p>
	Infos: <br />
	Sidelength: <?php echo $leaf->width; ?><br />
	Height: <?php echo $leaf->height; ?>
	</p>
	<p>
		Parent layer: 
		<?php echo CHtml::link($parentLayer->label, "#", 
						array("onclick" => "showLayerDetailsById(" . $parentLayer->id . ")")); ?>
	</p>
					
	<?php 
	if ($parentLayer->isVisible) {
		echo CHtml::button("Hide parent layer", array("onclick" => "leafRemoveLayerById(" . $parentLayer->id . ")"));
	} else {
		echo CHtml::button("Show parent layer", array("onclick" => "leafShowLayerById(" . $parentLayer->id . ")"));
	}
	?>
</div>