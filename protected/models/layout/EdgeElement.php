<?php 

class EdgeElement extends CActiveRecord {
	
	public $id;
	
	public $layoutId;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'LayoutEdgeElement';
	}

	public function relations() {
		return array(
		);
	}
	
}

?>