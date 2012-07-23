<?php 

class User extends CActiveRecord {
	
	public $id;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'User';
	}

	public function relations() {
		return array(
				'projects'=>array(self::HAS_MANY, 'Project', 'userId')
		);
	}
	
}

?>