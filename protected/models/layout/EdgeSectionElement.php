<?php 

class EdgeSectionElement extends CActiveRecord {
	
	public static $TYPE_DEFAULT = 0;
	public static $TYPE_END = 1;
	
	public $id;
	public $edgeId;
	
	public $type;
	
	public $translation;
	public $rotation;
	public $length;
	
	public $cylinderLength;
	public $coneLength;
	public $coneRadius;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'LayoutEdgeSectionElement';
	}

	public function relations() {
		return array(
				'user'=>array(self::BELONGS_TO, 'EdgeElement', 'edgeId'),
		);
	}
	
	public static function createDefaultEdgeSectionElement($edgeId, $translation, $rotation, $length) {
		$element = new self('insert');
		$element->edgeId = $edgeId;
	
		$element->type = EdgeSectionElement::$TYPE_DEFAULT;
		
		$element->translation = implode(' ', $translation);
		$element->rotation = $rotation;
		$element->length = $length;
	
		return $element;
	}
	
	public static function createAndSaveEndEdgeSectionElement($edgeId, $translation, $rotation, $length, $cylinderLength, $coneLength, $coneRadius) {
		$element = new self('insert');
		$element->edgeId = $edgeId;
	
		$element->type = EdgeSectionElement::$TYPE_END;
	
		$element->translation = implode(' ', $translation);
		$element->rotation = $rotation;
		$element->length = $length;
		
		$element->cylinderLength = $cylinderLength;
		$element->coneLength = $coneLength;
		$element->coneRadius = $coneRadius;
	
		$element->save();
	
		return $element;
	}
}

?>