<?php 

class TreeElement extends CActiveRecord
{
	public $id;
	public $label;
	public $parent_id;
	public $level;
	
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
		
		//TODO: Refactor, put an additional flag to table for leaf an layer
		//--> also in DotWriter...
		$this->content = TreeElement::model()->findAllByAttributes(array('parent_id'=>$this->id));
		
		if (count($this->content) > 0) {
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
	public static function createAndSaveTreeElement($label, $parent_id, $level)
	{
		$element = new self('insert');
		$element->label=$label;
		$element->parent_id=$parent_id;
		$element->level=$level;
		
		$element->save();
		return $element->id;
	}
}

?>