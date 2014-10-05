<?php
class LayerDetails extends CWidget {
 
    public $layerId;
 
    public function run() {
    	$box = BoxElement::model()->findByPk($this->layerId); 
    	
    	$layer = InputNode::model()->findByPk($box->inputTreeElementId);
    	$parent = InputNode::model()->findByPk($layer->parentId);
    	$children = InputTreeElement::model()->findAllByAttributes(array('parentId'=>$layer->id));
    	
        $this->render('layerDetails', array('layer' => $layer, 'parentLayer' => $parent, 'children' => $children));
    }
 
}
?>