<?php 

class Layout extends CActiveRecord {
	
	public static $TYPE_STRUCTURE = 0;
	public static $TYPE_DEPENDENCY_DETAIL = 1;
	public static $TYPE_DEPENDENCY_METRIC = 2;
	
	public $id;
	
	public $projectId;
	
	public $type;
	
	public $creationTime;
	
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
	
	public function getViewClass() {
		switch ($this->type) {
			case Layout::$TYPE_STRUCTURE:
				return new StructureView($this->id);
			break;
			case Layout::$TYPE_DEPENDENCY_DETAIL:
				return new DependencyView($this->id, DependencyView::$TYPE_DETAIL);
			break;
			case Layout::$TYPE_DEPENDENCY_METRIC:
				return new DependencyView($this->id, DependencyView::$TYPE_METRIC);
			break;
			default:
				//error
			break;
		}
	}
	
	public function getCreationTime() {
		if ($this->creationTime) {
			$date = new DateTime($this->creationTime);
			return $date->format('Y-m-d H:i:s');
		} else {
			return -1;
		}
	}
	
	public function setCreationTime(DateTime $date) {
		$mysqldate = date( 'Y-m-d H:i:s', $date->getTimestamp());
		$this->creationTime = $mysqldate;
	}
}

?>