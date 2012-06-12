<?php 

class LeafElement extends TreeElement
{
	
    public static function model($className=__CLASS__) {
 		return parent::model($className);
    }

    public function defaultScope(){
    	return array(
    			'condition'=>"isLeaf='1'"
    	);
    }
    
	public function accept($visitor) {
		return $visitor->visitLeafElement($this);
	}
	
	// factory method
	public static function createAndSave($label, $parent_id, $level) {
		$element = LeafElement::create($label, $parent_id, $level);
		
		$element->save();
		return $element->id;
	}
	
	public static function create($label, $parent_id, $level) {
		$element = new self('insert');
		$element->label=$label;
		$element->parent_id=$parent_id;
		$element->level=$level;
		$element->isLeaf = 1;
		
		return $element;
	}
}

?>