<?php 

class LeafElement extends TreeElement
{
	public $metric1;
	public $metric2;
	public $counter = 0;
	
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
	public static function createAndSave($name, $label, $parent_id, $level, $metric1 = 0, $metric2 = 0) {
		$element = LeafElement::create($name, $label, $parent_id, $level, $metric1, $metric2);
		
		$element->save();
		return $element->id;
	}
	
	public static function create($name, $label, $parent_id, $level, $metric1 = 0, $metric2 = 0) {
		$element = new self('insert');
		$element->label=$label;
		$element->name=$name;
		$element->parent_id=$parent_id;
		$element->level=$level;
		$element->isLeaf = 1;
		$element->metric1 = $metric1;
		$element->metric2 = $metric2;
		
		return $element;
	}
}

?>