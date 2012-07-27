<?php 

class InputNode extends InputTreeElement {
	
	public $isVisible = 1;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function defaultScope() {
		return array(
				'condition'=>"isLeaf='0'"
		);
	}
	
	public function setX3dInfos($x3dInfos) {
		$this->x3dInfos = serialize($x3dInfos);
		$this->save();
	}
	
	public function getX3dInfos() {
		return unserialize($this->x3dInfos);
	}
	
	public function accept($visitor) {
		$layoutElements = array();
		
		$content = InputNode::model()->findAllByAttributes(array('parent_id'=>$this->id));
		foreach ($content as $child) {
			$element = $child->accept($visitor);
			array_push($layoutElements, $element);
		}
		
		$content = InputLeaf::model()->findAllByAttributes(array('parent_id'=>$this->id));
		foreach ($content as $child) {
			$element = $child->accept($visitor);
			array_push($layoutElements, $element);
		}
			
		$edges = InputDependency::model()->findAllByAttributes(array('parent_id'=>$this->id));
		foreach ($edges as $edge) {
			array_push($layoutElements, $edge);
		}
			
		return $visitor->visitInputNode($this, $layoutElements);
	}
	
	// factory method
	public static function createAndSave($name, $label, $parent_id, $level) {
		$element = new self('insert');
		$element->name=$name;
		$element->label=$label;
		$element->parent_id=$parent_id;
		$element->level=$level;
		$element->isLeaf = 0;
	
		$element->save();
		return $element->id;
	}
	
	public static function create($name, $label, $parent_id, $level) {
		$element = new self('insert');
		$element->name=$name;
		$element->label=$label;
		$element->parent_id=$parent_id;
		$element->level=$level;
		$element->isLeaf = 0;
	
		$element->save();
		return $element;
	}
}

?>