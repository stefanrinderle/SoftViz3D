<?php 

class Layout extends CActiveRecord {
	
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