<?php
class LeafDetails extends CWidget {
 
    public $leafId;
 
    public function run() {
    	$leaf = InputLeaf::model()->findByPk($this->leafId);
    	
    	$parent = InputNode::model()->findByPk($leaf->parentId);
    	
        $this->render('leafDetails', array('leaf' => $leaf, 'parentLayer' => $parent));
    }
 
}
?>