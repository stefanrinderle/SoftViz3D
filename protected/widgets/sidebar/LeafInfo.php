<?php
class LeafInfo extends CWidget {
 
    public $leafId;
 
    public function run() {
    	$leaf = TreeElement::model()->findByPk($this->leafId);
    	$parent = TreeElement::model()->findByPk($leaf->parent_id);
    	
        $this->render('leafInfo', array(leaf => $leaf, parentLayer => $parent));
    }
 
}
?>