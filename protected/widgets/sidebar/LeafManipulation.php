<?php
class LeafManipulation extends CWidget {
 
    public $leafId;
    public $currentDepth;
    public $maxDepth;
    
    public function run() {
    	$leaf = TreeElement::model()->findByPk($this->leafId);
    	$parent = TreeElement::model()->findByPk($leaf->parent_id);
    	
        $this->render('leafManipulation', array(leaf => $leaf, parentLayer => $parent, 
        		'currentDepth' => $this->currentDepth, 'maxDepth' => $this->maxDepth));
    }
 
}
?>