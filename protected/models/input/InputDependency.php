<?php 

class InputDependency extends CActiveRecord {
	public $id;
	public $projectId;
	
	public $label;
	public $out_id;
	public $in_id;
	public $parent_id;
	public $counter = 1;
	public $isVisible = 1;
	
	public $maxCounter;
	
	//NOT IN DB
	public $out_parent_id;
	public $in_parent_id;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'InputDependency';
	}

	public function relations() {
		return array(
				'outElement'=>array(self::BELONGS_TO, 'InputTreeElement', 'out_id'),
				'inElement'=>array(self::BELONGS_TO, 'InputTreeElement', 'in_id')
				//'project'=>array(self::BELONGS_TO, 'Project', 'projectId'),
		);
	}
	
	// factory method
	public static function createDotInputDependency($label, $out_id, $in_id, $out_parent_id, $in_parent_id) {
		$element = new self('insert');
		$element->label = $label;
		$element->out_id = $out_id;
		$element->in_id = $in_id;
		$element->out_parent_id = $out_parent_id;
		$element->in_parent_id = $in_parent_id;
	
		$element->projectId = 0;
		
		return $element;
	}
	
	public static function createInputDependency($label, $out_id, $in_id, $parent_id = null) {
		$element = new self('insert');
		$element->label = $label;
		$element->out_id = $out_id;
		$element->in_id = $in_id;
		$element->parent_id = $parent_id;
		
		$element->projectId = 0;
		
		return $element;
	}
	
	public static function createAndSaveInputDependency($label, $out_id, $in_id, $parent_id = null) {
		$element = InputDependency::createInputDependency($label, $out_id, $in_id, $parent_id);
	
		$element->save();
		return $element->id;
	}
}

?>