<?php
class LeafDetails extends CWidget {
 
    public $leafId;
 
    public function run() {
    	$leaf = LeafElement::model()->findByPk($this->leafId);
    	$parent = LayerElement::model()->findByPk($leaf->parent_id);
    	
        $this->render('leafDetails', array(leaf => $leaf, parentLayer => $parent));
    }
 
}
?>