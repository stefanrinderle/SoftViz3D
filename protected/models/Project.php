<?php 

class Project extends CActiveRecord {
	
	public $id;
	
	public $userId;
	
	public $file;
	
	public $inputTreeRootId;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'Project';
	}

	public function relations() {
		return array(
				'user'=>array(self::BELONGS_TO, 'User', 'userId'),
				'inputTreeRoot'=>array(self::BELONGS_TO, 'InputTreeElement', 'inputTreeRootId'),
				'inputDependencies'=>array(self::HAS_MANY, 'InputDependency', 'projectId'),
				'layouts'=>array(self::HAS_MANY, 'Layout', 'projectId')
		);
	}
	
}

?>