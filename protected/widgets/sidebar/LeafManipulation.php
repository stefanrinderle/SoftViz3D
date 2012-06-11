<?php
class LeafManipulation extends CWidget {
 
    public $leafId;
 
    public function run() {
    	$leaf = TreeElement::model()->findByPk($this->leafId);
    	$parent = TreeElement::model()->findByPk($leaf->parent_id);
    	
        $this->render('leafManipulation', array(leaf => $leaf, parentLayer => $parent));
    }
 
}
?>