<?php
class LayerManipulation extends CWidget {
 
    public $layerId;
    public $currentDepth;
    public $maxDepth;
 
    public function run() {
    	$layer = InputNode::model()->findByPk($this->layerId);
    	
        $this->render('layerManipulation', array('layer' => $layer, 
        		'currentDepth' => $this->currentDepth, 'maxDepth' => $this->maxDepth));
    }
 
}
?>