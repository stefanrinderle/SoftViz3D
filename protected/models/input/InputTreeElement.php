<?php 

class InputTreeElement extends CActiveRecord {
	
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
	public $isLeaf;

	// not in database yet because not nesessary
	public $twoDimSize;
	
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