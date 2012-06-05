<?php
class EX3domLayerWidget extends CWidget {
	
	public $layer;
	// can be "tree" or "graph"
	public $type;
	
	public function run()
	{
		$x3dInfos = $this->layer->getX3dInfos();
		
		if ($this->type == "tree") {
			$this->render('x3dTreeLayer', array(graph=>$x3dInfos));
		} else {
			$this->render('x3dGraphLayer', array(graph=>$x3dInfos));
		}
	}
	
}

?>