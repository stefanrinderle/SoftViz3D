<?php
class LeafDetails extends CWidget {
 
    public $leafId;
 
    public function run() {
    	$leaf = TreeElement::model()->findByPk($this->leafId);
    	$parent = TreeElement::model()->findByPk($leaf->parent_id);
    	
        $this->render('leafDetails', array(leaf => $leaf, parentLayer => $parent));
    }
 
}
?>