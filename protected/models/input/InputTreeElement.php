<?php 

class InputTreeElement extends CActiveRecord {
	
	public static $TYPE_NODE = 0;
	public static $TYPE_LEAF = 1;
	public static $TYPE_LEAF_INTERFACE = 2;
	
	// db pk
	public $id;
	
	public $projectId;
	
	// input dot identifier
	public $name;
	
	// input dot label attribute
	public $label;
	
	// tree structure
	public $parentId;
	public $level;
	
	// ER-mapping of inheritance
	public $type;

	/**
	 * Proposed size for the next layout layer.
	 * Not in database yet because not nesessary.
	 */
	public $proposedSize;
	
	// max metric attributes necessary because CActiveRecord
	// needs it for the calculation 
	public $maxMetric1;
	public $maxMetric2;
	public $maxCounter;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'InputTreeElement';
	}

	public function relations() {
		return array(
				'parent'=>array(self::BELONGS_TO, 'InputTreeElement', 'parentId')
		);
	}
	
}

?>