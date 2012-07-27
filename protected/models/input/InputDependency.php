<?php 

class InputDependency extends CActiveRecord {
	
	public static $TYPE_INPUT_FLAT = 0;
	public static $TYPE_INPUT_FREE = 1;
	public static $TYPE_PATH = 2;
	
	public $id;
	public $projectId;
	
	public $label;
	public $out_id;
	public $in_id;
	public $parentId;
	public $counter = 1;
	public $isVisible = 1;
	
	public $maxCounter;
	
	public $type;
	
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
	public static function createAndSaveDotInputDependency($projectId, $label, $out_id, $in_id, $out_parent_id, $in_parent_id) {
		$element = new self('insert');
		
		if ($out_parent_id == $in_parent_id) {
			$element->type = InputDependency::$TYPE_INPUT_FLAT;
			$element->parentId = $out_parent_id;
		} else {
			$element->type = InputDependency::$TYPE_INPUT_FREE;
		}
		
		$element->label = $label;
		$element->out_id = $out_id;
		$element->in_id = $in_id;
	
		$element->projectId = $projectId;
		
		$element->save();
		
		return $element;
	}
	
	public static function createInputDependency($projectId, $label, $out_id, $in_id, $parentId = null) {
		$element = new self('insert');
		
		$element->type = InputDependency::$TYPE_PATH;
		
		$element->label = $label;
		$element->out_id = $out_id;
		$element->in_id = $in_id;
		$element->parentId = $parentId;
		
		$element->projectId = $projectId;
		
		return $element;
	}
}

?>