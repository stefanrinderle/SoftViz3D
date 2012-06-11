<div>
	Layer: <?php echo $layer->label; ?> <br />
	
	<br />
	<?php 
	echo CHtml::button("Remove layer", array("onclick" => "removeLayerById(" . $layer->id . ")")); 
	?> <?php 
	echo CHtml::button("Show layer", array("onclick" => "showLayerById(" . $layer->id . ")"));
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