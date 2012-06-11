<?php
class LayerManipulation extends CWidget {
 
    public $layerId;
 
    public function run() {
    	$layer = TreeElement::model()->findByPk($this->layerId);
    	
        $this->render('layerManipulation', array(layer => $layer));
    }
 
}
?>