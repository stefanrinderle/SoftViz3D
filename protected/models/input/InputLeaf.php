<?php 

class InputLeaf extends InputTreeElement {
	
	// input dot metric attributes
	public $metric1;
	public $metric2;
	
	//TODO: should be moved to layout model
	// counter needed for layout informations
	public $counter = 0;
	
    public static function model($className=__CLASS__) {
 		return parent::model($className);
    }

    public function defaultScope(){
    	return array(
    		'condition'=>'type=:leaf OR type=:interface', 
            'params'=>array(':leaf'=>InputTreeElement::$TYPE_LEAF, ':interface'=>InputTreeElement::$TYPE_LEAF_INTERFACE)
    	);
    }
    
	public function accept($visitor) {
		return $visitor->visitInputLeaf($this);
	}
	
	// factory method
	public static function createAndSave($projectId, $type, $name, $label, $parentId, $level, $metric1 = 0, $metric2 = 0) {
		$element = new self('insert');
		$element->projectId = $projectId;
		$element->type = $type;
		
		$element->label = $label;
		$element->name = $name;
		
		$element->parentId = $parentId;
		$element->level = $level;
		
		$element->metric1 = $metric1;
		$element->metric2 = $metric2;
		
		$element->save();
		return $element;
	}
	
}

?>