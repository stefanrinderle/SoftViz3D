<?php 

class InputNode extends InputTreeElement {
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function defaultScope() {
		return array(
			'condition'=>'type=:node', 
            'params'=>array(':node'=>InputTreeElement::$TYPE_NODE)
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
		
		$content = $visitor->layout->getAllChildInputNodes($this->id);
		foreach ($content as $child) {
			$element = $child->accept($visitor);
			array_push($layoutElements, $element);
		}
		
		$content = $visitor->layout->getAllChildInputLeaves($this->id);
		foreach ($content as $child) {
			$element = $child->accept($visitor);
			array_push($layoutElements, $element);
		}
			
		$edges = $visitor->layout->getAllChildInputDependencies($this->id);
		foreach ($edges as $edge) {
			array_push($layoutElements, $edge);
		}
			
		return $visitor->visitInputNode($this, $layoutElements);
	}
	
	// factory method
	public static function createAndSave($projectId, $name, $label, $parentId, $level) {
		$element = new self('insert');
		$element->projectId = $projectId; 
		$element->name = $name;
		$element->label = $label;
		$element->parentId = $parentId;
		$element->level = $level;
		$element->type = InputTreeElement::$TYPE_NODE;
	
		$element->save();
		return $element->id;
	}

}

?>