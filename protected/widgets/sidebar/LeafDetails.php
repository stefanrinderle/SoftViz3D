<?php
class LeafDetails extends CWidget {
 
    public $leafId;
 
    public function run() {
    	
		$box = BoxElement::model()->findByPk($this->leafId);
    	
 		$leaf = InputLeaf::model()->findByPk($box->inputTreeElementId);   	
    	$parent = InputNode::model()->findByPk($leaf->parentId);
    	
    	$parentBox = BoxElement::model()->findByAttributes(array('inputTreeElementId' => $parent->id));
    	
        $this->render('leafDetails', array('leaf' => $leaf, 'parentLayer' => $parent, 'parentBox' => $parentBox));
    }
 
}
?>