<div>
	<h3><?php echo $leaf->label; ?></h3>
	<p>
	Infos: <br />
	M1: <?php echo $leaf->metric1; ?><br />
	M2: <?php echo $leaf->metric2; ?>
	</p>
	<p>
		Parent layer: 
		<?php echo CHtml::link($parentLayer->label, "#", 
						array("onclick" => "showLayerDetailsById(" . $parentLayer->id . ")")); ?>
	</p>
					
	<?php 
	if ($parentBox->isVisible) {
		echo CHtml::button("Hide parent layer", array("onclick" => "leafRemoveLayerById(" . $parentBox->id . ")"));
	} else {
		echo CHtml::button("Show parent layer", array("onclick" => "leafShowLayerById(" . $parentBox->id . ")"));
	}
	?>
</div>