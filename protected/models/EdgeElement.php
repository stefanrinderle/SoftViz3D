<?php 

class EdgeElement extends CActiveRecord
{
	public $id;
	
	public $label;
	public $out_id;
	public $in_id;
	public $parent_id;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'tbl_EdgeElement';
	}

	public function relations()
	{
		return array(
				'outElement'=>array(self::BELONGS_TO, 'TreeElement', 'out_id'),
				'inElement'=>array(self::BELONGS_TO, 'TreeElement', 'in_id'),
		);
	}
	
	// factory method
	public static function createAndSaveEdgeElement($label, $out_id, $in_id, $parent_id = null)
	{
		$element = new self('insert');
		$element->label = $label;
		$element->out_id = $out_id;
		$element->in_id = $in_id;
		$element->parent_id = $parent_id;
		
		$element->save();
		return $element->id;
	}
}

?>