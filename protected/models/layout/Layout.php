<?php 

class Layout extends CActiveRecord {
	
	public static $TYPE_STRUCTURE = 0;
	public static $TYPE_DEPENDENCY_DETAIL = 1;
	public static $TYPE_DEPENDENCY_METRIC = 2;
	
	public $id;
	
	public $projectId;
	
	public $type;
	
	//$creationTime
	//$summary, statistics...
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'Layout';
	}

	public function relations() {
		return array(
			'BoxElements'=>array(self::HAS_MANY, 'BoxElement', 'layoutId'),
			'EdgeElements'=>array(self::HAS_MANY, 'EdgeElement', 'layoutId')
		);
	}
	
}

?>