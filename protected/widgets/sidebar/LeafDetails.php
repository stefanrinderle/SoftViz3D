<?php
class LeafDetails extends CWidget {
 
    public $leafId;
 
    public function run() {
    	$leaf = LeafElement::model()->findByPk($this->leafId);
    	
    	//print_r($this->leafId);
    	//print_r("<br /><br />");
    	
    	$parent = LayerElement::model()->findByPk($leaf->parent_id);
    	
        $this->render('leafDetails', array('leaf' => $leaf, 'parentLayer' => $parent));
    }
 
}
?>