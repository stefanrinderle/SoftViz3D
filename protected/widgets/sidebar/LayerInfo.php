<?php
class LayerInfo extends CWidget {
 
    public $layerId;
 
    public function run() {
    	$layer = TreeElement::model()->findByPk($this->layerId);
    	$parent = TreeElement::model()->findByPk($layer->parent_id);
    	$children = TreeElement::model()->findAllByAttributes(array('parent_id'=>$layer->id));
    	
        $this->render('layerInfo', array(layer => $layer, parentLayer => $parent, children => $children));
    }
 
}
?>