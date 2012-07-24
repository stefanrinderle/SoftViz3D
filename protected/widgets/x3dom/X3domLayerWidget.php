<?php
class X3domLayerWidget extends CWidget {
	
	public $layer;
	// can be "tree" or "graph"
	public $type;
	
	public function run() {
		$x3dInfos = $this->layer->getX3dInfos();
		
		if ($this->type == "tree") {
			// nothing more to do here
		} else {
			$this->render('x3dGraphLayer', array(graph=>$x3dInfos));
		}
		
		$elements = BoxElement::model()->findAll();
		
		
	}
	
}

?>