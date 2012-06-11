<div>
	Layer: <?php echo $layer->label; ?> <br />
	
	<br />
	<?php 
	echo CHtml::button("Remove layer", array("onclick" => "removeLayerById(" . $layer->id . ")")); 
	?> <?php 
	echo CHtml::button("Show layer", array("onclick" => "showLayerById(" . $layer->id . ")"));
	?>
	
	
</div>