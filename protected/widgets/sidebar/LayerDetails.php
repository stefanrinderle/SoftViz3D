<?php
class LayerDetails extends CWidget {
 
    public $layerId;
 
    public function run() {
    	$layer = LayerElement::model()->findByPk($this->layerId);
    	$parent = LayerElement::model()->findByPk($layer->parent_id);
    	$children = TreeElement::model()->findAllByAttributes(array('parent_id'=>$layer->id));
    	
        $this->render('layerDetails', array('layer' => $layer, 'parentLayer' => $parent, 'children' => $children));
    }
 
}
?>