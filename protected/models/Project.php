<?php 

class Project extends CActiveRecord {
	
	public $id;
	public $userId;
	
	public $name;
	
	public $file;
	public $fileUpdateTime;
	
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
	
	public function saveNewFileString($fileString) {
		$data = mysql_real_escape_string($fileString);
		$this->file = $data;
		$this->setFileUpdateTime(new DateTime());
		
		$this->save();
	}
	
	public function getFileStringArray() {
		$data = $this->file;
		$data = explode("\\n", $data);
		
		foreach ($data as $key => $value) {
			$data[$key] = stripcslashes($value);
		}
		
		return $data;
	}
	
	public function getFileUpdateTime() {
		if ($this->fileUpdateTime) {
			$date = new DateTime($this->fileUpdateTime);
			return $date->format('Y-m-d H:i:s');
		} else {
			return -1;
		}
	}
	
	public function getLayoutTypeArray() {
		$result = array();
		foreach ($this->layouts as $layout) {
			$result[$layout->type] = $layout;
		}
		return $result;
	}
	private function setFileUpdateTime(DateTime $date) {
		$mysqldate = date( 'Y-m-d H:i:s', $date->getTimestamp());
		$this->fileUpdateTime = $mysqldate;
		$this->save();
	}
}

?>