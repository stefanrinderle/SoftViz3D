<?php 

class TreeElement extends CActiveRecord
{
	public $id;
	public $label;
	public $parent_id;
	public $level;
	
	public $isLeaf;
	
	// not in database yet
	public $x3dInfos;
	public $size;
	public $content;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
			$this->content = TreeElement::model()->findAllByAttributes(array('parent_id'=>$this->id));
			
			foreach ($this->content as $child) {
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
		return TreeElement::createAndSaveTreeElement($label, $parent_id, $level, true);
	}
}

?>