<?php
class LayerDetails extends CWidget {
 
    public $layerId;
 
    public function run() {
    	$layer = InputNode::model()->findByPk($this->layerId);
    	$parent = InputNode::model()->findByPk($layer->parent_id);
    	$children = InputTreeElement::model()->findAllByAttributes(array('parent_id'=>$layer->id));
    	
        $this->render('layerDetails', array('layer' => $layer, 'parentLayer' => $parent, 'children' => $children));
    }
 
}
?>