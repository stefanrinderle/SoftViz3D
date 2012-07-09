<?php 

class TreeElement extends CActiveRecord
{
	public $id;
	//public $label;
	public $name = "";
	public $parent_id;
	public $level;
	public $isLeaf;

	// not in database yet because not nesessary
	public $twoDimSize;
	
	// TODO: used for x3d calc - could maybe be refactored
	public $max_level;
	
	public $maxMetric1;
	public $maxMetric2;
	public $maxCounter;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'tbl_TreeElement';
	}

	public function relations()
	{
		return array(
				'parent'=>array(self::BELONGS_TO, 'TreeElement', 'parent_id'),
		);
	}
	
}

?>