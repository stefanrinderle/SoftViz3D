<?php 

class TreeElement extends CActiveRecord
{
	public $id;
	public $label;
	public $parent_id;
	public $level;
	
	public $isLeaf;

	public $x3dInfos;
	
	// not in database yet because not nesessary
	public $size;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function setX3dInfos($x3dInfos) {
		$this->x3dInfos = serialize($x3dInfos);
		$this->save();
	}
	
	public function getX3dInfos() {
		return unserialize($this->x3dInfos);
	}
	
	public function tableName()
	{
		return 'tbl_TreeElement';
	}
	
	public function relations()
	{
		return array(
				'parent'=>array(self::BELONGS_TO, 'TreeElement', 'parent_id'),
		);
	}

	public function accept($visitor) {
		$layoutElements = array();
		
		if (!$this->isLeaf) {
			$content = TreeElement::model()->findAllByAttributes(array('parent_id'=>$this->id));
			
			foreach ($content as $child) {
				$element = $child->accept($visitor);
				array_push($layoutElements, $element);
			}
			
			$edges = EdgeElement::model()->findAllByAttributes(array('parent_id'=>$this->id));
			foreach ($edges as $edge) {
				array_push($layoutElements, $edge);
			}
			
			return $visitor->visitTreeElement($this, $layoutElements);
		} else {
			return $visitor->visitLeafTreeElement($this);
		}
	}
	
	// factory method
	public static function createAndSaveTreeElement($label, $parent_id, $level, $isLeaf = false)
	{
		$element = new self('insert');
		$element->label=$label;
		$element->parent_id=$parent_id;
		$element->level=$level;
		$element->isLeaf = $isLeaf;
		
		$element->save();
		return $element->id;
	}
	
	// factory method
	public static function createAndSaveLeafTreeElement($label, $parent_id, $level)
	{
		TreeElement::createAndSaveTreeElement($label, $parent_id, $level, true);
	}
}

?>