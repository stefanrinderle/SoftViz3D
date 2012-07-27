<?php
class LayerDetails extends CWidget {
 
    public $layerId;
 
    public function run() {
    	$layer = InputNode::model()->findByPk($this->layerId);
    	$parent = InputNode::model()->findByPk($layer->parentId);
    	$children = InputTreeElement::model()->findAllByAttributes(array('parentId'=>$layer->id));
    	
        $this->render('layerDetails', array('layer' => $layer, 'parentLayer' => $parent, 'children' => $children));
    }
 
}
?>